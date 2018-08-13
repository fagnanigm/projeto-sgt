<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Motoristas;
use Classes\Utilities;


$app->post('/motoristas/insert', function (Request $request, Response $response, array $args) {
	$motoristas = new motoristas($this->db);
	$data = $motoristas->insert($request->getParams());
	return $response->withJson($data);
});

$app->get('/motoristas/get', function (Request $request, Response $response, array $args) {
	$motoristas = new Motoristas($this->db);
	$data = $motoristas->get($request->getParams());
	return $response->withJson($data);
});

$app->get('/motoristas/get/{id}', function (Request $request, Response $response, array $args) {
	$motoristas = new motoristas($this->db);
	$data = $motoristas->get_by_id($args['id'], $request->getParams());
	return $response->withJson($data);
});

$app->post('/motoristas/delete', function (Request $request, Response $response, array $args) {
	$motoristas = new motoristas($this->db);
	$data = $motoristas->delete($request->getParams());
	return $response->withJson($data);
});

$app->post('/motoristas/update', function (Request $request, Response $response, array $args) {
	$motoristas = new motoristas($this->db);
	$data = $motoristas->update($request->getParams());
	return $response->withJson($data);
});

?>