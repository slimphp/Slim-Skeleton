<?php

class TestBaseController {

  protected $ci;

  public function __construct($ci) {
    $this->ci = $ci;
  }

  public function test($params, $response) {

    //return $response->withJson($_SERVER, 200, JSON_UNESCAPED_UNICODE);

    throw new Exception("[Hello] Error Processing Request", 450);


    $stmt = $this->ci->addb->prepare('
      SELECT * FROM ad101db.withdraws WHERE idx > 90;
    ');
    $stmt->execute([]);
    $rows = $stmt->fetchAll();

    return $response->withJson($rows, 200, JSON_UNESCAPED_UNICODE);
  }

}
