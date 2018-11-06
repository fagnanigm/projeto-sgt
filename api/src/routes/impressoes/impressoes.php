<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Utilities;
use Classes\Impressoes;

// Imprimir cotação
$app->post('/impressoes/cotacao', function (Request $request, Response $response, array $args) {
	$impressoes = new Impressoes($this->db);
	$data = $impressoes->print_cotacao($request->getParams());
	return $response->withJson($data);
});

// Imprimir AS
$app->post('/impressoes/as', function (Request $request, Response $response, array $args) {
	$impressoes = new Impressoes($this->db);
	$data = $impressoes->print_as($request->getParams(), $this->user_request);
	return $response->withJson($data);
});

// Imprimir OCC
$app->post('/impressoes/occ', function (Request $request, Response $response, array $args) {
	$impressoes = new Impressoes($this->db);
	$data = $impressoes->print_occ($request->getParams());
	return $response->withJson($data);
});

// Imprimir ND
$app->post('/impressoes/nd', function (Request $request, Response $response, array $args) {
	$impressoes = new Impressoes($this->db);
	$data = $impressoes->print_nd($request->getParams());
	return $response->withJson($data);
});

// Imprimir Fatura
$app->post('/impressoes/fatura', function (Request $request, Response $response, array $args) {
	$impressoes = new Impressoes($this->db);
	$data = $impressoes->print_fatura($request->getParams());
	return $response->withJson($data);
});

// Imprimir Recibo
$app->post('/impressoes/recibo', function (Request $request, Response $response, array $args) {
	$impressoes = new Impressoes($this->db);
	$data = $impressoes->print_recibo($request->getParams());
	return $response->withJson($data);
});


?>