<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\ValidadesProposta;

// Inserção de validade de proposta
$app->post('/validades-proposta/insert', function (Request $request, Response $response, array $args) {
	$validades_proposta = new ValidadesProposta($this->db);
	$data = $validades_proposta->insert($request->getParams());
	return $response->withJson($data);
});

// Seleção de todas as validades de proposta
$app->get('/validades-proposta/get', function (Request $request, Response $response, array $args) {
	$validades_proposta = new ValidadesProposta($this->db);
	$data = $validades_proposta->get($request->getParams());
	return $response->withJson($data);
});

// Seleção de validade da proposta por ID
$app->get('/validades-proposta/get/{id}', function (Request $request, Response $response, array $args) {
	$validades_proposta = new ValidadesProposta($this->db);
	$data = $validades_proposta->get_by_id($args['id'], $request->getParams());
	return $response->withJson($data);
});

// Remoção de validades da proposta
$app->post('/validades-proposta/delete', function (Request $request, Response $response, array $args) {
	$params = $request->getParams();
	$validades_proposta = new ValidadesProposta($this->db);
	$data = $validades_proposta->delete($params);
	return $response->withJson($data);
});

// Update de validades da proposta
$app->post('/validades-proposta/update', function (Request $request, Response $response, array $args) {
	$params = $request->getParams();
	$validades_proposta = new ValidadesProposta($this->db);
	$data = $validades_proposta->update($params);
	return $response->withJson($data);
});

?>