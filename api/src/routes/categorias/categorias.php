<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Categorias;
use Classes\Utilities;

$app->post('/categorias/insert', function (Request $request, Response $response, array $args) {
	$categorias = new Categorias($this->db);
	$data = $categorias->insert($request->getParams());
	return $response->withJson($data);
});

$app->post('/categorias/update', function (Request $request, Response $response, array $args) {
	$categorias = new Categorias($this->db);
	$data = $categorias->update($request->getParams());
	return $response->withJson($data);
});

$app->get('/categorias/get', function (Request $request, Response $response, array $args) {
	$categorias = new Categorias($this->db);
	$data = $categorias->get($request->getParams());
	return $response->withJson($data);
});

$app->post('/categorias/delete', function (Request $request, Response $response, array $args) {
	$categorias = new Categorias($this->db);
	$data = $categorias->delete($request->getParams());
	return $response->withJson($data);
});

$app->get('/categorias/get/{id}', function (Request $request, Response $response, array $args) {
	$categorias = new Categorias($this->db);
	$data = $categorias->get_by_id($args['id'], $request->getParams());
	return $response->withJson($data);
});

?>