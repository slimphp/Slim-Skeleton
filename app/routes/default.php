<?php

$app->get('/', function () use ($app)
{
    // Sample log message
    // $app->log->info("Slim-Skeleton '/' route");

    // Render index view
    $app->render('index.html.twig');
});