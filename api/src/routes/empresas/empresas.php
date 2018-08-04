<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Empresas;
use Classes\Utilities;

$app->post('/empresas/insert', function (Request $request, Response $response, array $args) {
	$empresas = new Empresas($this->db);
	$data = $empresas->insert($request->getParams());
	return $response->withJson($data);
});

$app->get('/empresas/get', function (Request $request, Response $response, array $args) {
	$empresas = new Empresas($this->db);
	$data = $empresas->get($request->getParams());
	return $response->withJson($data);
});

$app->get('/empresas/get/{id}', function (Request $request, Response $response, array $args) {
	$empresas = new Empresas($this->db);
	$data = $empresas->get_by_id($args['id']);
	return $response->withJson($data);
});

$app->post('/empresas/delete', function (Request $request, Response $response, array $args) {
	$empresas = new Empresas($this->db);
	$data = $empresas->delete($request->getParams());
	return $response->withJson($data);
});

$app->post('/empresas/update', function (Request $request, Response $response, array $args) {
	$empresas = new Empresas($this->db);
	$data = $empresas->update($request->getParams());
	return $response->withJson($data);
});




?>