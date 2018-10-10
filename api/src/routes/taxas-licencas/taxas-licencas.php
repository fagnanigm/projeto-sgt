<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\TaxasLicencas;

// Inserção de taxas e licencas
$app->post('/taxas-licencas/insert', function (Request $request, Response $response, array $args) {
	$taxas_licencas = new TaxasLicencas($this->db);
	$data = $taxas_licencas->insert($request->getParams());
	return $response->withJson($data);
});

// Seleção de todas os tipos de taxas e licencas
$app->get('/taxas-licencas/get', function (Request $request, Response $response, array $args) {
	$taxas_licencas = new TaxasLicencas($this->db);
	$data = $taxas_licencas->get($request->getParams());
	return $response->withJson($data);
});

// Seleção de tipo de taxa por ID
$app->get('/taxas-licencas/get/{id}', function (Request $request, Response $response, array $args) {
	$taxas_licencas = new TaxasLicencas($this->db);
	$data = $taxas_licencas->get_by_id($args['id'], $request->getParams());
	return $response->withJson($data);
});

?>