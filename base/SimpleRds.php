<?php

class SimpleRds {

  protected $pdo;

  public function __construct($pdo) {
    $this->pdo = $pdo;
  }


  /**
   * 이름으로 디비 핸들러를 리턴
   * $this->ci->$name 와 같은 식으로 할수도 있는데, AWS EB 환경에서는 에러발생.
   */
  public function getDb($name) {
    if ($name=='funddb') return $this->ci->funddb;
    if ($name=='screendb') return $this->ci->screendb;
    if ($name=='authdb') return $this->ci->authdb;
    if ($name=='addb') return $this->ci->addb;
  }
  private function escapeTableName($str) {
    if ($str[0]=='"') return $str; // 이미 따옴표가 붙어있으면 그대로 패스
    else return '"'.str_replace('.', '"."', $str).'"';
  }


  public function count($db, $query, $binds=[]) {

    $stmt = $this->getDb($db)->prepare($query);
    $stmt->execute($binds);

    $count = 0;
    while( $row = $stmt->fetch(PDO::FETCH_NUM) ) {
      $count += $row[0];
    }

    return (int)$count;
  }

  /*
    $result = $this->ci->dbutil->paging([
      'db'=>'funddb',
      'select' => '*',
      'query' => '
        SELECT %%
        FROM "Loan"."LoanRequestItem"
        WHERE '.$where.'
      ',
      'binds' => $binds,
      'limit' => $params['limit'],
      'offset' => $params['offset'],
      'orderby' => '"CreatedAt" ASC'
    ]);
  */
  public function paging($cmd) {

    $db = $this->getDb($cmd['db']);

    $countQuery = isset($cmd['countQuery']) ? $cmd['countQuery'] : $cmd['query'];
    $totalCount = $this->count(
      $cmd['db'],
      str_replace('%%', 'count(*)', $countQuery),
      $cmd['binds']
    );

    $stmt = $db->prepare(
      str_replace('%%', $cmd['select'], $cmd['query'])
      .( isset($cmd['orderby']) ? ' ORDER BY '.$cmd['orderby'] : '' )
      .' LIMIT :limit OFFSET :offset'
    );
    $cmd['binds']['limit'] = $cmd['limit'];
    $cmd['binds']['offset'] = $cmd['offset'];
    $stmt->execute($cmd['binds']);
    $items = $this->fetchAllWithJson($stmt);
    if ($items[0]===null) $items = [];

    return [ 'totalCount'=>(int) $totalCount, 'items'=>$items ];
  }

  private function isJson($value) {
    return (
      ( substr($value, 0, 1)=='{' && substr($value, -1)=='}' )
      || ( substr($value, 0, 1)=='[' && substr($value, -1)==']' )
    );
  }

  /**
   * json 파싱하여 리스트 어레이를 리턴
   * 쿼리 결과 없으면 빈 어레이를 리턴한다.
   */
  public function fetchAllWithJson($stmt, $useList=true) {
    $list = [];
    while($row = $stmt->fetch()) {

      foreach($row as &$value) {

        if (is_string($value)) $value = trim($value);

        /////////////////////
        // json decode
        ////////////////////
        if ($this->isJson($value)) $value = json_decode($value, true);

        ////////////////////////
        // 숫자 저글링
        ////////////////////////
        else $value = $this->ci->util->juggleNumber($value);


      }

      $list[] = $row;
    }

    if (!$list && $useList) return [null]; // list($item) 과 같은 식으로 받을 수 있게
    else return $list;
  }
  public function fetch($db, $query, $binds) {
    $stmt = $this->getDb($db)->prepare($query);
    $stmt->execute($binds);
    $rows = $this->fetchAllWithJson($stmt);
    return $rows[0];
  }
  public function fetchAll($db, $query, $binds) {
    $stmt = $this->getDb($db)->prepare($query);
    $stmt->execute($binds);
    $rows = $this->fetchAllWithJson($stmt, false);
    return $rows;
  }

  // 검색쿼리 작성
  public function makeSearchQuery($search) {
    if ( is_numeric($search) && strlen($search)===4 )
      return ' AND substring("Phone" from LENGTH("Phone")-3 for 4) = :search ';
    else if ( is_numeric($search) && strlen($search)===6 )
      return ' AND left("Safekey", 6) = :search';
    else
      return ' AND trim("Name") = :search ';
  }

  // 캐시를 검사하여 만료 전이면 가져오고, 만료이면 false 리턴.
  public function getCache($key) {

    $stmt = $this->ci->funddb->prepare('
    SELECT data FROM "Fund101".cache WHERE
      (
        expiration > CURRENT_TIMESTAMP + interval \'9 hours\'
        OR expiration IS NULL
      )
      AND id = :id
    ');
    $stmt->execute([ 'id' => $key ]);

    $list = $this->fetchAllWithJson($stmt);

    if ($list) return $list[0]['data'];
    else return false;
  }
  public function putCache($key, $data, $expiration) {

    $stmt = $this->ci->funddb->prepare('
      UPDATE "Fund101".cache SET
        data = :data,
        expiration = CURRENT_TIMESTAMP + interval \'9 hours\' + interval \''.$expiration.'\'
      WHERE id = :id
    ');
    $stmt->execute([
      'data' => json_encode($data),
      'id' => $key
    ]);
  }

  /**
   * prepare쿼리 시, 선택적으로 파라메터를 사용할수 있도록 도와준다.
   * @return array [ 조건문, 바인드어레이 ]
   */
  public function helpPrepare($params, $defs=[]) {
    $where = '1=1';
    $binds = [];
    foreach($defs as $def) {

      $val = null;
      if (is_array($def['param'])) {
        foreach($def['param'] as $alias) {
          if (isset($params[$alias])) $val = $params[$alias];
        }
      }
      else {
        if (isset($params[$def['param']])) $val = $params[$def['param']];
      }

      if ( $val===null && isset($def['default']) ) $val = $def['default'];

      if ($val) {
        if ( isset($def['type']) && $def['type']=='int' ) $val = (int) $val;
        $where .= ' AND "'.$def['column'].'" = :'.$def['column'];
        $binds[$def['column']] = $val;
      }

    }

    return [$where, $binds];
  }

  public function insert($db, $table, $list) {

    $cols = [];
    $valsList = [];
    $binds = [];

    foreach($list as $data) {
      $vals = [];
      foreach($data as $col=>$val) {
        $cols[] = $col;
        $vals[] = '?';
        $binds = $this->bindValue($binds, $val);
      }
      $valsList[] = '('.implode(",", $vals).')';
    }

    $query = '
      INSERT INTO '.$this->escapeTableName($table).'
      ("'.implode('","', $cols).'")
      VALUES '.implode(',',$valsList).'
    ';
    //return $binds;
    $stmt = $this->getDb($db)->prepare($query);
    $stmt->execute($binds);
  }

  public function update($db, $table, $data, $condition) {

    $binds = [];
    $set = [];
    $where = [];

    foreach($data as $key=>$val) {
      $set[] = "\"{$key}\" = ?";
      $binds = $this->bindValue($binds, $val);
    }

    foreach($condition as $key=>$val) {
      $where[] = "\"{$key}\" = ?";
      $binds = $this->bindValue($binds, $val);
    }

    $stmt = $this->getDb($db)->prepare("
      UPDATE ".$this->escapeTableName($table)."
      SET ".implode(', ', $set)."
      WHERE ".implode(' AND ', $where)."
    ");
    return $stmt->execute($binds);
  }

  private function bindValue($binds, $val) {

    if (is_array($val)) $binds[] = json_encode($val, JSON_UNESCAPED_UNICODE);
    else if ($val===true) $binds[] = 'true';
    else if ($val===false) $binds[] = 'false';
    else $binds[] = $val;

    return $binds;
  }

  public function listToArray($rows, $col) {
    $list = [];
    foreach($rows as $row) $list[] = $row[$col];
    return $list;
  }
  public function listToInQuery($rows, $col) {
    $array = $this->listToArray($rows, $col);
    return $this->arrayToInQuery($array);
  }
  public function arrayToInQuery($array) {
    return "'". implode("','", $array) ."'";
  }

}
