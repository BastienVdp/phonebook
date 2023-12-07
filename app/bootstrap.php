<?php

require __DIR__ . '/../vendor/autoload.php';

$config = include __DIR__ . '/../app/config.php';

$app = new App\Core\Application(
    dirname(__DIR__),
    $config
);

return $app;
