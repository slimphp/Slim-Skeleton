<?php

$app->get('/{name}', function ($params, $response) {
  return $response->withJson($params, 200, JSON_UNESCAPED_UNICODE);
});
