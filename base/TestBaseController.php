<?php

class TestBaseController {

  protected $ci;

  public function __construct($ci) {
    $this->ci = $ci;
  }

  public function test($params, $response) {
    try {

      throw new Exception('error');

      return $response->withJson($params, 200, JSON_UNESCAPED_UNICODE);

    } catch (Exception $e) {
      return $response->withStatus($e->getCode())->write($e->getMessage());
    }
  }

}
