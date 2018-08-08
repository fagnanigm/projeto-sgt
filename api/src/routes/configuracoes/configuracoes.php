<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Configuracoes;
use Classes\Utilities;

$app->post('/configuracoes/insert', function (Request $request, Response $response, array $args) {
	$configuracoes = new Configuracoes($this->db);
	$data = $configuracoes->insert($request->getParams());
	return $response->withJson($data);
});

$app->get('/configuracoes/get/{term}', function (Request $request, Response $response, array $args) {
	$configuracoes = new Configuracoes($this->db);
	$data = $configuracoes->get_by_args($args['term']);
	return $response->withJson($data);
});

$app->post('/configuracoes/update', function (Request $request, Response $response, array $args) {
	$configuracoes = new Configuracoes($this->db);
	$data = $configuracoes->update($request->getParams());
	return $response->withJson($data);
});


?>