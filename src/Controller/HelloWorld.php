<?php

namespace Controller;
use Slim\Slim;

class HelloWorld{

    public function greetings($name){
        $app = Slim::getInstance();
        // Sample log message
        $app->log->info("Slim-Skeleton '/hello/:name' route");
        // Render index view
        $app->render('index.twig',array(
            "name"=>$name
        ));
    }
}