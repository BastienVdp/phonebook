<?php

$config = require __DIR__ . '/../app/config.php';

require __DIR__ . '/../vendor/autoload.php';

$seed = in_array('--seed', $argv);
$refresh = in_array('--refresh', $argv);

$runner = new App\Core\Runner($config);

if($runner->applyMigrations($refresh)) {
    if($seed) {
        $runner->applySeeders();
    }
}