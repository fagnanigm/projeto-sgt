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
	$data = $impressoes->print_as($request->getParams());
	return $response->withJson($data);
});


?>