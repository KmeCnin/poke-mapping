<?php

require_once __DIR__.'/../vendor/autoload.php';
$config = parse_ini_file(__DIR__.'/../config/config.ini');

$app = new Silex\Application();

$app->get(
    '/heatmap/pokemon/{id}',
    function (Silex\Application $app, $id) use ($config) {
        $pdo = new PDO(
            'mysql:host='.$config['host'].';dbname='.$config['dbname'],
            $config['user'],
            $config['pass']
        );
        $query = $pdo->prepare('SELECT latitude, longitude FROM pokemon WHERE pokemon_id = :id');
        $query->execute([':id' => $id]);
        $heatmap = $query->fetchAll();
        return include(__DIR__.'/../src/KmeCnin/PokeMapping/View/heatmap-pokemon.php');
    }
);

$app->run();
