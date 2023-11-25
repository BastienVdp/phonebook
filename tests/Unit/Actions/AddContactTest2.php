<?php

use Database\Factories\UserFactory;
use App\Actions\Contact\AddContactAction;

require '../../Pest.php';

it('can add contact', function () {
    
	$user = UserFactory::create();

    $result = (new AddContactAction())->execute(
		'test',
		'test',
		'test@test.fr',
		'0606060606',
		'Famille',
		'',
		0,
		$user[0]->id
	);

	expect($result)->toBeTrue();
});
