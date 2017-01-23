<?php

require __DIR__ . '/SimpleRds.php';

class TestBaseController {

  protected $ci;

  public function __construct($ci) {
    $this->ci = $ci;
  }

  public function test($params, $response) {

    $db = new SimpleRds($this->ci->librarydb);

    // $db->insert('books.book', [
    //   [ 'name' => '좋은책', 'author'=> '신사고', 'time' => date('c') ],
    //   [ 'name' => '미래생활사전', 'author'=> '페이스 팝콘', 'time' => date('r') ],
    // ]);



    $rows = $db->paging([
      'query' => "SELECT %% FROM books.도서",
      'select' => '*',
      'binds' => [],
      'limit' => 2,
      'offset' => 0,
      'orderby' => 'id DESC'
    ]);

    return $response->withJson($rows, 200, JSON_UNESCAPED_UNICODE);

  }

}
