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
        
        if (is_numeric($id)) {
            // Get heatmap of a pokemon
            $query = $pdo->prepare('
                SELECT latitude, longitude
                FROM pokemon
                WHERE pokemon_id = :id
            ');
            $query->execute([':id' => $id]);
        } elseif (is_string($id)) {
            // Get heatmap by rarity
            $rarities = [];
            switch ($id) {
                case 'common':
                    $rarities[] = 'Common';
                case 'uncommon':
                    $rarities[] = 'Uncommon';
                case 'rare':
                    $rarities[] = 'Rare';
                case 'very-rare':
                    $rarities[] = 'Very rare';
                case 'ultra-rare':
                    $rarities[] = 'Ultra rare';
                    break;
            }
            $query = $pdo->prepare('
                SELECT latitude, longitude
                FROM pokemon
                INNER JOIN pokedex
                ON pokemon.pokemon_id = pokedex.id
                WHERE pokedex.rarity
                IN('.implode(', ', array_fill(0, count($rarities), '?')).')
            ');
            $query->execute($rarities);
        } else {
            throw new Exception('Invalid id or rarity.');
        }
        $heatmap = $query->fetchAll();
        
        // Get pokedex data
        $query = $pdo->query('
            SELECT id, name_fr
            FROM pokedex
            WHERE id <= 151
            ORDER BY name_fr
        ');
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
