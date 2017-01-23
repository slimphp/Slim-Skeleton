<?php

class SimpleRds {

  protected $pdo;

  public function __construct($pdo) {
    $this->pdo = $pdo;
  }

  private function escapeTableName($str) {
    return '"'.str_replace('.', '"."', $str).'"';
  }

  private function bindValue($binds, $val) {
    if (is_array($val)) $binds[] = json_encode($val, JSON_UNESCAPED_UNICODE);
    else if ($val===true) $binds[] = 'true';
    else if ($val===false) $binds[] = 'false';
    else $binds[] = $val;

    return $binds;
  }

  public function insert($table, $list) {

    $cols = [];
    foreach($list[0] as $col=>$val) $cols[] = $col;

    $valsList = [];
    $binds = [];

    foreach($list as $data) {
      $vals = [];
      foreach($data as $col=>$val) {
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

    $stmt = $this->pdo->prepare($query);
    $stmt->execute($binds);
  }

  public function fetchAll($query, $binds) {
    $stmt = $this->pdo->prepare($query);
    $stmt->execute($binds);
    return $stmt->fetchAll();
  }

  public function fetch($query, $binds) {
    $rows = $this->fetchAll($query, $binds);
    return $rows[0];
  }

  public function count($query, $binds) {
    $row = $this->fetch($query, $binds);
    return (int)$row['count'];
  }

  public function paging($cmd) {

    $countQuery = $cmd['countQuery'] ?? $cmd['query'];
    $total = $this->count(
      str_replace('%%', 'count(*)', $countQuery),
      $cmd['binds']
    );

    $newQuery = str_replace('%%', $cmd['select'], $cmd['query'])
      .( $cmd['orderby'] ? ' ORDER BY '.$cmd['orderby'] : '' )
      .' LIMIT ? OFFSET ?';
    $newBinds = $cmd['binds'];
    $newBinds[] = $cmd['limit'];
    $newBinds[] = $cmd['offset'];
    $items = $this->fetchAll($newQuery, $newBinds);

    return [
      'total' => $total,
      'items' => $items
    ];
  }

  public function update($table, $data, $condition) {

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

    $stmt = $this->pdo->prepare("
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
