<?php

require_once __DIR__.'/../vendor/autoload.php';
$config = parse_ini_file(__DIR__.'/../config/config.ini');

$app = new Silex\Application();
$app['debug'] = $config['debug'];
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

$app->get(
    '/heatmap/pokemon/{id}',
    function (Silex\Application $app, $id) use ($app, $config) {
        // Connect to database
        $pdo = new PDO(
            'mysql:host='.$config['host'].';dbname='.$config['dbname'],
            $config['user'],
            $config['pass']
        );
        $pdo->exec("SET CHARACTER SET utf8");
        
        // Get heatmap
        $query = $pdo->prepare(
            'SELECT latitude, longitude FROM pokemon WHERE pokemon_id = :id'
        );
        $query->execute([':id' => $id]);
        $heatmap = $query->fetchAll();
        
        // Get pokedex data
        $query = $pdo->query('SELECT id, name FROM pokedex WHERE id <= 151');
        $pokedex = $query->fetchAll(PDO::FETCH_KEY_PAIR);
        
        return $app['twig']->render('heatmap-pokemon.twig', array(
            'pokemon_id' => $id,
            'heatmap' => $heatmap,
            'pokedex' => $pokedex,
            'config' => $config,
        ));
    }
);

$app->run();
