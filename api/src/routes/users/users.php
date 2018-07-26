<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\User;
use Classes\Utilities;

$app->get('/users/get', function (Request $request, Response $response, array $args) {
	$user = new User($this->db);
	$data = $user->get($request->getParams());
	return $response->withJson($data);
});

$app->post('/users/insert', function (Request $request, Response $response, array $args) {
	$user = new User($this->db);
	$data = $user->insert($request->getParams());
	return $response->withJson($data);
});

$app->post('/users/delete', function (Request $request, Response $response, array $args) {
	$user = new User($this->db);
	$data = $user->delete($request->getParams());
	return $response->withJson($data);
});

$app->post('/users/update', function (Request $request, Response $response, array $args) {
	$user = new User($this->db);
	$data = $user->update($request->getParams());
	return $response->withJson($data);
});

$app->post('/users/login', function (Request $request, Response $response, array $args) {
	$user = new User($this->db);
	$data = $user->login($request->getParams());
	return $response->withJson($data);
});

$app->get('/users/get/{id}', function (Request $request, Response $response, array $args) {
	$user = new User($this->db);
	$data = $user->get_by_id($args['id']);
	return $response->withJson($data);
});



?>