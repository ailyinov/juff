<?php

use Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';
require dirname(__DIR__).'/config/routes/routes.php';

$projectDir = realpath(dirname(__DIR__ ));

$config = new \Juff\Kernel\Config();

$env = @include $projectDir . '/env.php';
if (is_array($env)) {
    foreach ($env as $k => $v) {
        $config->put($k, $v);
    }
}

$dotenv = Dotenv::createImmutable($projectDir);
$dotenv->load();

$loader = new \Twig\Loader\FilesystemLoader($projectDir . '/templates');
$twig = new \Twig\Environment($loader, [
//    'cache' => $projectDir . '/var/cache/twig',
]);

$eventConfig = include($projectDir . '/config/events.php');
$events = [];
foreach ($eventConfig as $event => $setListeners) {
    $setListeners();
    $events[] = $event;
}
