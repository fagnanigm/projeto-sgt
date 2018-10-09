<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\PrazoRazoes;

// Inserção de razão do prazo
$app->post('/prazo-razoes/insert', function (Request $request, Response $response, array $args) {
	$prazo_razoes = new PrazoRazoes($this->db);
	$data = $prazo_razoes->insert($request->getParams());
	return $response->withJson($data);
});

// Seleção de todas as razões do prazo
$app->get('/prazo-razoes/get', function (Request $request, Response $response, array $args) {
	$prazo_razoes = new PrazoRazoes($this->db);
	$data = $prazo_razoes->get($request->getParams());
	return $response->withJson($data);
});

// Seleção da razão por ID
$app->get('/prazo-razoes/get/{id}', function (Request $request, Response $response, array $args) {
	$prazo_razoes = new PrazoRazoes($this->db);
	$data = $prazo_razoes->get_by_id($args['id'], $request->getParams());
	return $response->withJson($data);
});

// Remoção da razão
$app->post('/prazo-razoes/delete', function (Request $request, Response $response, array $args) {
	$params = $request->getParams();
	$prazo_razoes = new PrazoRazoes($this->db);
	$data = $prazo_razoes->delete($params);
	return $response->withJson($data);
});

// Update da razão
$app->post('/prazo-razoes/update', function (Request $request, Response $response, array $args) {
	$params = $request->getParams();
	$prazo_razoes = new PrazoRazoes($this->db);
	$data = $prazo_razoes->update($params);
	return $response->withJson($data);
});

?>