<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\TaxasLicencasCategorias;

// Inserção de tipo de taxas e licencas
$app->post('/taxas-licencas/categorias/insert', function (Request $request, Response $response, array $args) {
	$taxas_licencas_categorias = new TaxasLicencasCategorias($this->db);
	$data = $taxas_licencas_categorias->insert($request->getParams());
	return $response->withJson($data);
});

// Seleção de todas os tipos de taxas e licencas
$app->get('/taxas-licencas/categorias/get', function (Request $request, Response $response, array $args) {
	$taxas_licencas_categorias = new TaxasLicencasCategorias($this->db);
	$data = $taxas_licencas_categorias->get($request->getParams());
	return $response->withJson($data);
});

// Seleção de tipo de taxa por ID
$app->get('/taxas-licencas/categorias/get/{id}', function (Request $request, Response $response, array $args) {
	$taxas_licencas_categorias = new TaxasLicencasCategorias($this->db);
	$data = $taxas_licencas_categorias->get_by_id($args['id'], $request->getParams());
	return $response->withJson($data);
});

// Remoção do tipo da taxa
$app->post('/taxas-licencas/categorias/delete', function (Request $request, Response $response, array $args) {
	$params = $request->getParams();
	$taxas_licencas_categorias = new TaxasLicencasCategorias($this->db);
	$data = $taxas_licencas_categorias->delete($params);
	return $response->withJson($data);
});

// Update do tipo da taxa
$app->post('/taxas-licencas/categorias/update', function (Request $request, Response $response, array $args) {
	$params = $request->getParams();
	$taxas_licencas_categorias = new TaxasLicencasCategorias($this->db);
	$data = $taxas_licencas_categorias->update($params);
	return $response->withJson($data);
});


?>