<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Locais;
use Classes\Utilities;

$app->post('/locais/insert', function (Request $request, Response $response, array $args) {
	$locais = new Locais($this->db);
	$data = $locais->insert($request->getParams());
	return $response->withJson($data);
});

$app->get('/locais/get', function (Request $request, Response $response, array $args) {
	$locais = new Locais($this->db);
	$data = $locais->get($request->getParams());
	return $response->withJson($data);
});

$app->get('/locais/get/{id}', function (Request $request, Response $response, array $args) {
	$locais = new Locais($this->db);
	$data = $locais->get_by_id($args['id'], $request->getParams());
	return $response->withJson($data);
});

$app->post('/locais/delete', function (Request $request, Response $response, array $args) {
	$locais = new Locais($this->db);
	$data = $locais->delete($request->getParams());
	return $response->withJson($data);
});

$app->post('/locais/update', function (Request $request, Response $response, array $args) {
	$locais = new Locais($this->db);
	$data = $locais->update($request->getParams());
	return $response->withJson($data);
});




?>