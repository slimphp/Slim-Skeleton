<?php
namespace base;

use Slim\Interfaces\InvocationStrategyInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RouteStrategy implements InvocationStrategyInterface {

	public function __invoke(
    callable $callable,
    ServerRequestInterface $request,
    ResponseInterface $response,
    array $routeArguments
  ) {

    $params = array_merge(
      $this->getParams($request),
      $routeArguments
    );

    return call_user_func($callable, $params, $response);
	}

  // 숫자와 전화번호 처리
  private function juggleNumber($value) {

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
  private function getParams($request) {

    $query = is_array($request->getQueryParams()) ? $request->getQueryParams() : [];
    $body = is_array($request->getParsedBody()) ? $request->getParsedBody() : [];

    $data = array_merge($query, $body);

    // 어레이 depth 가 1보다 클때에는, 아래 array_map 이 문제가 된다.
    //$data = array_map('trim', $data);

    foreach($data as $key=>$val) {
      if (!is_array($val)) $data[$key] = $this->juggleNumber($data[$key]);
    }

    return $data;
  }


}
