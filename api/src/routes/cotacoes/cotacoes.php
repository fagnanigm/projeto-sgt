<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Cotacoes;
use Classes\Utilities;


$app->post('/cotacoes/insert', function (Request $request, Response $response, array $args) {
	$cotacoes = new Cotacoes($this->db);
	$data = $cotacoes->insert($request->getParams());
	return $response->withJson($data);
});

$app->get('/cotacoes/get', function (Request $request, Response $response, array $args) {
	$cotacoes = new Cotacoes($this->db);
	$data = $cotacoes->get($request->getParams());
	return $response->withJson($data);
});

$app->get('/cotacoes/get/{id}', function (Request $request, Response $response, array $args) {
	$cotacoes = new Cotacoes($this->db);
	$data = $cotacoes->get_by_id($args['id'], $request->getParams());
	return $response->withJson($data);
});

$app->post('/cotacoes/delete', function (Request $request, Response $response, array $args) {
	$cotacoes = new Cotacoes($this->db);
	$data = $cotacoes->delete($request->getParams());
	return $response->withJson($data);
});

$app->post('/cotacoes/update', function (Request $request, Response $response, array $args) {
	$cotacoes = new Cotacoes($this->db);
	$data = $cotacoes->update($request->getParams());
	return $response->withJson($data);
});

$app->post('/cotacoes/getnextcode', function (Request $request, Response $response, array $args) {
	$cotacoes = new Cotacoes($this->db);
	$data = $cotacoes->getnextcode($request->getParams());
	return $response->withJson($data);
});

$app->get('/cotacoes/revisoes/get/{id}', function (Request $request, Response $response, array $args) {
	$revisoes = new Cotacoes($this->db);
	$data = $revisoes->get_revisoes($args['id']);
	return $response->withJson($data);
});

$app->post('/cotacoes/approve', function (Request $request, Response $response, array $args) {
	$cotacoes = new Cotacoes($this->db);
	$data = $cotacoes->cotacao_approve($request->getParams());
	return $response->withJson($data);
});

?>