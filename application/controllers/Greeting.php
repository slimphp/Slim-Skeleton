<?php namespace application\controllers;

class Greeting extends Controller
{
    public function sayHello($name)
    {
    	$this->$app->view->setData('name', $name);
        $this->$app->render('hello.html');
    }
}