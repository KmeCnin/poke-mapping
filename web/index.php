<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app->get('/pokemon/{id}', function (Silex\Application $app, $id) {
    
});

$app->run();
