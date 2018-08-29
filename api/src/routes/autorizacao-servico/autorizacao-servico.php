<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\AutorizacaoServico;
use Classes\Utilities;

$app->post('/as/insert', function (Request $request, Response $response, array $args) {
	$as = new AutorizacaoServico($this->db);
	$data = $as->insert($request->getParams());
	return $response->withJson($data);
});

$app->get('/as/getnextcode', function (Request $request, Response $response, array $args) {
	$as = new AutorizacaoServico($this->db);
	$data = $as->getnextcode($request->getParams());
	return $response->withJson($data);
});

/*


$app->get('/AutorizacaoServico/get', function (Request $request, Response $response, array $args) {
	$AutorizacaoServico = new AutorizacaoServico($this->db);
	$data = $AutorizacaoServico->get($request->getParams());
	return $response->withJson($data);
});

$app->get('/AutorizacaoServico/get/{id}', function (Request $request, Response $response, array $args) {
	$AutorizacaoServico = new AutorizacaoServico($this->db);
	$data = $AutorizacaoServico->get_by_id($args['id'], $request->getParams());
	return $response->withJson($data);
});

$app->post('/AutorizacaoServico/delete', function (Request $request, Response $response, array $args) {
	$AutorizacaoServico = new AutorizacaoServico($this->db);
	$data = $AutorizacaoServico->delete($request->getParams());
	return $response->withJson($data);
});

$app->post('/AutorizacaoServico/update', function (Request $request, Response $response, array $args) {
	$AutorizacaoServico = new AutorizacaoServico($this->db);
	$data = $AutorizacaoServico->update($request->getParams());
	return $response->withJson($data);
});

*/




?>