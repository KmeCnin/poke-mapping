<?php

spl_autoload_register(function ($class) {
    require '..\src\\'.$class.'.php';
});

use KmeCnin\PokeMapping\RawData; 

$config = parse_ini_file(__DIR__.'\..\config\config.ini');

$rawData = new RawData(
    $config['server_url'],
    [
        'pokemon' => 'true',
        'pokestops' => 'false',
        'gyms' => 'false',
        'scanned' => 'false',
    ]
);
echo $rawData->pullAsJson();
