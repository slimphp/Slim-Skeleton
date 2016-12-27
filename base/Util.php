<?php
namespace base;

class Util {

  // 입력값 처리 /////////////////////////////////////////////////////

  // 숫자와 전화번호 처리
  public function juggleNumber($value) {

    // 전화번호
    if ( substr($value, 0, 1)==='0' && strlen($value)>7 && !strpos($value, '.') ) {
      return $value;
    }

    // integer, float
    else if ( is_numeric($value) ) {
      return $value + 0;
    }

    // string
    else {
      return $value;
    }
  }

  // 요청방법,기본값을 고려한 파라메터
  public function getParams($request, $defaults=[]) {

    $query = is_array($request->getQueryParams()) ? $request->getQueryParams() : [];
    $body = is_array($request->getParsedBody()) ? $request->getParsedBody() : [];

    $data = array_merge($query, $body);

    // 어레이 depth 가 1보다 클때에는, 아래 array_map 이 문제가 된다.
    //$data = array_map('trim', $data);

    foreach($data as $key=>$val) {
      if (!is_array($val)) $data[$key] = $this->juggleNumber($data[$key]);
    }

    foreach($defaults as $key=>$val) {
      if ( $val===false && !isset($data[$key]) ) throw new Exception($key.' 파라메터가 필요합니다.');
      else if (!isset($data[$key])) $data[$key] = $val;
    }

    return $data;
  }



}
