<?php

class TestBaseController {

  protected $ci;

  public function __construct($ci) {
    $this->ci = $ci;
  }

  public function test($params, $response) {
    try {

      throw new Exception('error');

    throw new Exception("[Hello] Error Processing Request", 450);

    } catch (Exception $e) {
      return $response->withStatus($e->getCode())->write($e->getMessage());
    }
  }

}
