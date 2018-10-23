<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\TextosPadroes;

// Definição de texto padrão
$app->post('/textos-padroes/set', function (Request $request, Response $response, array $args) {

	$textos_padroes = new TextosPadroes($this->db);
	$data = $textos_padroes->set($request->getParams());

	return $response->withJson($data);
});


// Seleção de todos os textos padrões
$app->get('/textos-padroes/get', function (Request $request, Response $response, array $args) {

	$textos_padroes = new TextosPadroes($this->db);
	$data = $textos_padroes->get($request->getParams());

	return $response->withJson($data);
});

// Seleção de texto padrão por termo
$app->get('/textos-padroes/get/{term}', function (Request $request, Response $response, array $args) {

	$textos_padroes = new TextosPadroes($this->db);
	$data = $textos_padroes->get_by_term($args['term'], $request->getParams());

	return $response->withJson($data);
});


?>