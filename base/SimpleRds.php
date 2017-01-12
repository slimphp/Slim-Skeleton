<?php

class SimpleRds {

  protected $pdo;

  public function __construct($pdo) {
    $this->pdo = $pdo;
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

  public function delete($table, $condition, $limit=1) {

    list($where, $binds) = $this->getCondition($condition);

    $query = "DELETE FROM {$table} WHERE {$where} LIMIT {$limit}";

    $stmt = $this->pdo->prepare($query);
    $stmt->execute($binds);

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
