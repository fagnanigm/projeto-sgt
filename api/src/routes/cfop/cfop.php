<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Cfop;

// Inserção de CFOP
$app->post('/cfop/insert', function (Request $request, Response $response, array $args) {
	$cfop = new Cfop($this->db);
	$data = $cfop->insert($request->getParams());
	return $response->withJson($data);
});

// Seleção de todos os CFOP
$app->get('/cfop/get', function (Request $request, Response $response, array $args) {
	$cfop = new Cfop($this->db);
	$data = $cfop->get($request->getParams());
	return $response->withJson($data);
});

// Seleção de CFOP por ID
$app->get('/cfop/get/{id}', function (Request $request, Response $response, array $args) {
	$cfop = new Cfop($this->db);
	$data = $cfop->get_by_id($args['id'], $request->getParams());
	return $response->withJson($data);
});

// Remoção de CFOP
$app->post('/cfop/delete', function (Request $request, Response $response, array $args) {
	$params = $request->getParams();
	$cfop = new Cfop($this->db);
	$data = $cfop->delete($params);
	return $response->withJson($data);
});

// Update de CFOP
$app->post('/cfop/update', function (Request $request, Response $response, array $args) {
	$params = $request->getParams();
	$cfop = new Cfop($this->db);
	$data = $cfop->update($params);
	return $response->withJson($data);
});

// Procura de CFOP
$app->get('/cfop/search', function (Request $request, Response $response, array $args) {
	$cfop = new Cfop($this->db);
	$data = $cfop->search($request->getParams());
	return $response->withJson($data);
});

// Importa do OMIE
$app->post('/cfop/importOmie', function (Request $request, Response $response, array $args) {
	$cfop = new Cfop($this->db);
	$data = $cfop->importOmie($request->getParams());
	return $response->withJson($data);
});

?>