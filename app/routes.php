<?php 

$app->group('/api', function() use ($app) {
	$app->get('[/]', 'App\Controllers\UserController:index')
	->setName('user.index');

	$app->post('/register', 'App\Controllers\UserController:register');

	$app->put('/update/{id}', 'App\Controllers\UserController:update')->setName('user.update');

	$app->put('/softdelete/{id}', 'App\Controllers\UserController:softDelete')->setName('user.softDelete');

	$app->delete('/harddelete/{id}', 'App\Controllers\UserController:hardDelete')->setName('user.hardDelete');

	$app->get('/user/{id}', 'App\Controllers\UserController:findUser')->setName('user.find');
});
