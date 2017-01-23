<?php

require __DIR__ . '/SimpleRds.php';

class TestBaseController {

  protected $ci;

  public function __construct($ci) {
    $this->ci = $ci;
  }

  public function test($params, $response) {

    $db = new SimpleRds($this->ci->librarydb);

    $db->insert('books.book', [
      [ 'name' => '좋은책', 'author'=> '신사고', 'time' => date('c') ],
      [ 'name' => '미래생활사전', 'author'=> '페이스 팝콘', 'time' => date('r') ],
    ]);

  }

}
