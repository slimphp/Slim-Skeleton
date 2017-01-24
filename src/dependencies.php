<?php

// $CONFIG: db config
require __DIR__ . '/../config.php';


// DIC configuration

$container = $app->getContainer();

$container['stage'] = $stage;


if ($stage=='prod') {
  $container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
      $code = $exception->getCode();
      $code = ($code>=400 && $code<600) ? $code : 500 ;
      $message = $exception->getMessage();
      if ($message[0]=='[') {
        $arr = explode(']', $message);
        $reasonPhrase = substr($arr[0], 1);
        $message = trim($arr[1]);
      }
      return $c['response']
        ->withStatus($code, $reasonPhrase ?? 'Error')
        ->write($message);
    };
  };
}

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// Custom RouteStrategy
require __DIR__ . '/../base/RouteStrategy.php';
$container['foundHandler'] = function() {
  return new \base\RouteStrategy();
};


// Db connection
foreach($CONFIG['rds'] as $name => $data) {

  if ($stage=='dev') $info = $data[$stage];
  else $info = array_merge($data['dev'], $data[$stage]);

  $container[$name] = function ($c) use ($info) {

    $dsn = [];
    foreach ($info['dsn'] as $key => $val) {
      $dsn[] = "{$key}={$val}";
    }

    $pdo = new PDO(
      $info['type'] .':'. implode(';', $dsn),
      $info['user'],
      $info['pass']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
    return $pdo;
  };
}



$container['util'] = function ($c) { return new Util($c); };
