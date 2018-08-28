<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Clientes;
use Classes\Utilities;

$app->post('/clientes/insert', function (Request $request, Response $response, array $args) {
	$clientes = new Clientes($this->db);
	$data = $clientes->insert($request->getParams());
	return $response->withJson($data);
});

$app->get('/clientes/get', function (Request $request, Response $response, array $args) {
	$clientes = new Clientes($this->db, 10);
	$data = $clientes->get($request->getParams());
	return $response->withJson($data);
});

$app->post('/clientes/delete', function (Request $request, Response $response, array $args) {
	$clientes = new Clientes($this->db);
	$data = $clientes->delete($request->getParams());
	return $response->withJson($data);
});

$app->get('/clientes/get/{id}', function (Request $request, Response $response, array $args) {
	$clientes = new Clientes($this->db);
	$data = $clientes->get_by_id($args['id'], $request->getParams());
	return $response->withJson($data);
});

$app->post('/clientes/importOmie', function (Request $request, Response $response, array $args) {
	$clientes = new Clientes($this->db);
	$data = $clientes->importOmie($request->getParams());
	return $response->withJson($data);
});

$app->get('/clientes/search', function (Request $request, Response $response, array $args) {
	$clientes = new Clientes($this->db);
	$data = $clientes->search($request->getParams());
	return $response->withJson($data);
});



/*

$app->post('/users/update', function (Request $request, Response $response, array $args) {
	$user = new User($this->db);
	$data = $user->update($request->getParams());
	return $response->withJson($data);
});

*/



?>