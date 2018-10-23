<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\EquipTiposComerciais;

// Inserção de tipos comerciais
$app->post('/equipamentos-tipos-comerciais/insert', function (Request $request, Response $response, array $args) {

	$equipamentos_tipos_comerciais = new EquipTiposComerciais($this->db);
	$data = $equipamentos_tipos_comerciais->insert($request->getParams());

	return $response->withJson($data);
});


// Seleção de todos os tipos comerciais
$app->get('/equipamentos-tipos-comerciais/get', function (Request $request, Response $response, array $args) {

	$equipamentos_tipos_comerciais = new EquipTiposComerciais($this->db);
	$data = $equipamentos_tipos_comerciais->get($request->getParams());

	return $response->withJson($data);
});


// Seleção de tipo comercial por ID
$app->get('/equipamentos-tipos-comerciais/get/{id}', function (Request $request, Response $response, array $args) {

	$equipamentos_tipos_comerciais = new EquipTiposComerciais($this->db);
	$data = $equipamentos_tipos_comerciais->get_by_id($args['id'], $request->getParams());

	return $response->withJson($data);
});


// Remoção de tipo comercial
$app->post('/equipamentos-tipos-comerciais/delete', function (Request $request, Response $response, array $args) {

	$params = $request->getParams();

	$equipamentos_tipos_comerciais = new EquipTiposComerciais($this->db);
	$data = $equipamentos_tipos_comerciais->delete($params);

	return $response->withJson($data);
});

// Update de tipo comercial
$app->post('/equipamentos-tipos-comerciais/update', function (Request $request, Response $response, array $args) {

	$params = $request->getParams();

	$equipamentos_tipos_comerciais = new EquipTiposComerciais($this->db);
	$data = $equipamentos_tipos_comerciais->update($params);

	return $response->withJson($data);
});



?>