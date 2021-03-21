<?php

$env = strtoupper($_ENV['ENV']);

return array(
    'slim'=>array(
        'debug'=>$env=='DEV'?true:false,
        'templates.path' => '..'.DIRECTORY_SEPARATOR.'templates',
        'cookies.encrypt'=>$_ENV['COOKIES_ENCRYPT'],
        'cookies.lifetime'=>$_ENV['COOKIES_LIFETIME'],
        'cookies.secret_key'=>$_ENV['COOKIES_SECRET']

    ),
    'logger'=>array(
        'name'=>$_ENV['APP_NAME']
    ),
    'view'=>array(
        'charset' => 'utf-8',
        'cache' => realpath('..'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'cache'),
        'auto_reload' => true,
        'strict_variables' => false,
        'autoescape' => true
    )
);