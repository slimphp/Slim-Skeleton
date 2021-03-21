<?php
use Controller\HelloWorld;

$app->get('/hello/:name', array(HelloWorld::class, 'greetings'));