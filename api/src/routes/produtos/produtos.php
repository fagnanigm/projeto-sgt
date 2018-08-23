<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Produtos;
use Classes\Utilities;

$app->post('/produtos/insert', function (Request $request, Response $response, array $args) {
	$produtos = new Produtos($this->db);
	$data = $produtos->insert($request->getParams());
	return $response->withJson($data);
});

$app->post('/produtos/update', function (Request $request, Response $response, array $args) {
	$produtos = new Produtos($this->db);
	$data = $produtos->update($request->getParams());
	return $response->withJson($data);
});

$app->get('/produtos/get', function (Request $request, Response $response, array $args) {
	$produtos = new Produtos($this->db, 10);
	$data = $produtos->get($request->getParams());
	return $response->withJson($data);
});

$app->post('/produtos/delete', function (Request $request, Response $response, array $args) {
	$produtos = new Produtos($this->db);
	$data = $produtos->delete($request->getParams());
	return $response->withJson($data);
});

$app->get('/produtos/get/{id}', function (Request $request, Response $response, array $args) {
	$produtos = new Produtos($this->db);
	$data = $produtos->get_by_id($args['id'], $request->getParams());
	return $response->withJson($data);
});

$app->post('/produtos/importOmie', function (Request $request, Response $response, array $args) {
	$produtos = new Produtos($this->db);
	$data = $produtos->importOmie($request->getParams());
	return $response->withJson($data);
});

?>