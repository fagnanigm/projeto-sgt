<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Projetos;
use Classes\Utilities;

$app->post('/projetos/insert', function (Request $request, Response $response, array $args) {
	$projetos = new Projetos($this->db);
	$data = $projetos->insert($request->getParams());
	return $response->withJson($data);
});

$app->get('/projetos/get', function (Request $request, Response $response, array $args) {
	$projetos = new Projetos($this->db);
	$data = $projetos->get($request->getParams());
	return $response->withJson($data);
});

$app->get('/projetos/get/{id}', function (Request $request, Response $response, array $args) {
	$projetos = new Projetos($this->db);
	$data = $projetos->get_by_id($args['id'], $request->getParams());
	return $response->withJson($data);
});

$app->post('/projetos/delete', function (Request $request, Response $response, array $args) {
	$projetos = new Projetos($this->db);
	$data = $projetos->delete($request->getParams());
	return $response->withJson($data);
});

$app->post('/projetos/update', function (Request $request, Response $response, array $args) {
	$projetos = new Projetos($this->db);
	$data = $projetos->update($request->getParams());
	return $response->withJson($data);
});


?>