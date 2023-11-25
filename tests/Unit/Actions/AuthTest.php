<?php

use App\Core\Factory;
use App\Actions\Auth\LoginAction;
use Database\Factories\UserFactory;
use App\Actions\Auth\RegisterAction;

beforeAll(function () {
    $app = require_once dirname(__DIR__) . '/../../app/bootstrap.php';
    Factory::init($app->database->pdo);
    UserFactory::clean();
});

it('should generate a JWT token on successful login', function () {
    
    $user = UserFactory::create();

    $result = (new LoginAction())->execute($user->email, 'password');

    expect($result)->not->toBeEmpty();
    expect($result)->toBeString();
});

it('should handle login failure and return errors', function () {

    $result = (new LoginAction())->execute('invalid_user', 'invalid_password');

    expect($result)->toHaveKey('errors');
    expect($result['errors'])->toBeArray();
});


it('should generate a JWT token on successful register', function () {
    
    $result = (new RegisterAction())->execute(
		'test@test.fr',
		'test',
		'test',
		'test',
		'test'
	);

    expect($result)->not->toBeEmpty();
    expect($result)->toBeString();
});

