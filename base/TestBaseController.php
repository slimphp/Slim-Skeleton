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



    // $rows = $db->paging([
    //   'query' => "SELECT %% FROM books.도서",
    //   'select' => '*',
    //   'binds' => [],
    //   'limit' => 2,
    //   'offset' => 0,
    //   'orderby' => 'id DESC'
    // ]);

    // $db->update('books.도서', [
    //   'author' => '홍길동'
    // ], [
    //   'id' => 6
    // ]);
    //
    // $rows = $db->fetchAll('
    //   SELECT * FROM books.도서 WHERE id = ?
    // ', [ 6 ]);


    // $rows = $db->delete('books.도서', [ 'id' => 4 ]);

    return $response->withJson($this->ci->stage, 200, JSON_UNESCAPED_UNICODE);


    $rows = $db->fetchAll("
      SELECT * FROM books.도서
      WHERE author IN ".$db->arrayToInQuery(['신사고'])."
    ", [
    ]);

    return $response->withJson($rows, 200, JSON_UNESCAPED_UNICODE);

  }

}
