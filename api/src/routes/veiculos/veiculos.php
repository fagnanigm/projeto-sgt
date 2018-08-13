<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Veiculos;
use Classes\Utilities;

$app->post('/veiculos/insert', function (Request $request, Response $response, array $args) {
	$veiculos = new Veiculos($this->db);
	$data = $veiculos->insert($request->getParams());
	return $response->withJson($data);
});

$app->get('/veiculos/get', function (Request $request, Response $response, array $args) {
	$veiculos = new Veiculos($this->db);
	$data = $veiculos->get($request->getParams());
	return $response->withJson($data);
});

$app->get('/veiculos/get/{id}', function (Request $request, Response $response, array $args) {
	$veiculos = new Veiculos($this->db);
	$data = $veiculos->get_by_id($args['id'], $request->getParams());
	return $response->withJson($data);
});

$app->post('/veiculos/delete', function (Request $request, Response $response, array $args) {
	$veiculos = new Veiculos($this->db);
	$data = $veiculos->delete($request->getParams());
	return $response->withJson($data);
});

$app->post('/veiculos/update', function (Request $request, Response $response, array $args) {
	$veiculos = new Veiculos($this->db);
	$data = $veiculos->update($request->getParams());
	return $response->withJson($data);
});



?>