<?php

use App\Core\Factory;

beforeAll(function () {
    $app = require_once dirname(__DIR__) . '/../../app/bootstrap.php';
    Factory::init($app->database->pdo);
});

afterAll(function () {
    Factory::$faker = null;
    Factory::$pdo = null;
});