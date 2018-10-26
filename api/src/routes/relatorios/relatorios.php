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


?>