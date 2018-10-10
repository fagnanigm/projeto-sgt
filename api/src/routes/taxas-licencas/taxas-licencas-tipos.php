<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\TaxasLicencasTipos;

// Inserção de tipo de taxas e licencas
$app->post('/taxas-licencas/tipos/insert', function (Request $request, Response $response, array $args) {
	$taxas_licencas_tipos = new TaxasLicencasTipos($this->db);
	$data = $taxas_licencas_tipos->insert($request->getParams());
	return $response->withJson($data);
});

// Seleção de todas os tipos de taxas e licencas
$app->get('/taxas-licencas/tipos/get', function (Request $request, Response $response, array $args) {
	$taxas_licencas_tipos = new TaxasLicencasTipos($this->db);
	$data = $taxas_licencas_tipos->get($request->getParams());
	return $response->withJson($data);
});

// Seleção de tipo de taxa por ID
$app->get('/taxas-licencas/tipos/get/{id}', function (Request $request, Response $response, array $args) {
	$taxas_licencas_tipos = new TaxasLicencasTipos($this->db);
	$data = $taxas_licencas_tipos->get_by_id($args['id'], $request->getParams());
	return $response->withJson($data);
});

// Remoção do tipo da taxa
$app->post('/taxas-licencas/tipos/delete', function (Request $request, Response $response, array $args) {
	$params = $request->getParams();
	$taxas_licencas_tipos = new TaxasLicencasTipos($this->db);
	$data = $taxas_licencas_tipos->delete($params);
	return $response->withJson($data);
});

// Update do tipo da taxa
$app->post('/taxas-licencas/tipos/update', function (Request $request, Response $response, array $args) {
	$params = $request->getParams();
	$taxas_licencas_tipos = new TaxasLicencasTipos($this->db);
	$data = $taxas_licencas_tipos->update($params);
	return $response->withJson($data);
});


?>