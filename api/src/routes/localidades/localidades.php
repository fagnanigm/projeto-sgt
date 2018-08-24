<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Localidades;
use Classes\Utilities;

$app->post('/localidades/refresh', function (Request $request, Response $response, array $args) {
	$localidades = new Localidades($this->db);
	$data = $localidades->refresh($request->getParams());
	return $response->withJson($data);
});

$app->get('/localidades/get/ufs', function (Request $request, Response $response, array $args) {
	$localidades = new Localidades($this->db);
	$data = $localidades->getAllUfs();
	return $response->withJson($data);
});

$app->get('/localidades/get/uf/{term}', function (Request $request, Response $response, array $args) {
	$localidades = new Localidades($this->db);
	$data = $localidades->getUf($args['term']);
	return $response->withJson($data);
});

$app->get('/localidades/get/municipios/{term}', function (Request $request, Response $response, array $args) {
	$localidades = new Localidades($this->db);
	$data = $localidades->getMunicipios($args['term']);
	return $response->withJson($data);
});

$app->get('/localidades/get/municipio/{term}', function (Request $request, Response $response, array $args) {
	$localidades = new Localidades($this->db);
	$data = $localidades->getMunicipio($args['term']);
	return $response->withJson($data);
});


?>