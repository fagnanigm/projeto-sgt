<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Relatorios;

// Relatório de AS
$app->post('/relatorios/relatorio-as', function (Request $request, Response $response, array $args) {

	$relatorios = new Relatorios($this->db);
	$data = $relatorios->generate_relatorios_as($request->getParams());

	return $response->withJson($data);
});


// COTAÇÕES - STATUS
$app->post('/relatorios/cotacao/status', function (Request $request, Response $response, array $args) {

	$relatorios = new Relatorios($this->db);
	$data = $relatorios->generate_cotacao_status($request->getParams());

	return $response->withJson($data);
});

// COTAÇÕES - VENDEDORES
$app->post('/relatorios/cotacao/vendedores', function (Request $request, Response $response, array $args) {

	$relatorios = new Relatorios($this->db);
	$data = $relatorios->generate_cotacao_vendedores($request->getParams());

	return $response->withJson($data);
});

// COTAÇÕES - PERÍODO
$app->post('/relatorios/cotacao/periodo', function (Request $request, Response $response, array $args) {

	$relatorios = new Relatorios($this->db);
	$data = $relatorios->generate_cotacao_periodo($request->getParams());

	return $response->withJson($data);
});

// COTAÇÕES - CLIENTE
$app->post('/relatorios/cotacao/cliente', function (Request $request, Response $response, array $args) {

	$relatorios = new Relatorios($this->db);
	$data = $relatorios->generate_cotacao_cliente($request->getParams());

	return $response->withJson($data);
});

// COTAÇÕES - ORIGEM / DESTINO
$app->post('/relatorios/cotacao/origem-destino', function (Request $request, Response $response, array $args) {

	$relatorios = new Relatorios($this->db);
	$data = $relatorios->generate_cotacao_origem_destino($request->getParams());

	return $response->withJson($data);
});

// COTAÇÕES - DIMENSIONAL
$app->post('/relatorios/cotacao/dimensional', function (Request $request, Response $response, array $args) {

	$relatorios = new Relatorios($this->db);
	$data = $relatorios->generate_cotacao_dimensional($request->getParams());

	return $response->withJson($data);
});


// PROJETOS - CLIENTES
$app->post('/relatorios/projeto/cliente', function (Request $request, Response $response, array $args) {

	$relatorios = new Relatorios($this->db);
	$data = $relatorios->generate_projeto_cliente($request->getParams());

	return $response->withJson($data);
});

// PROJETOS - PERIODO
$app->post('/relatorios/projeto/periodo', function (Request $request, Response $response, array $args) {

	$relatorios = new Relatorios($this->db);
	$data = $relatorios->generate_projeto_periodo($request->getParams());

	return $response->withJson($data);
});


?>