<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\IncluirOS;
use Classes\Utilities;

$app->post('/incluir-os/insert', function (Request $request, Response $response, array $args) {
	$incluir-servico = new IncluirServico($this->db);
	$data = $projetos->insert($request->getParams());
	return $response->withJson($data);
});

$app->get('/incluir-os/get', function (Request $request, Response $response, array $args) {
	$projetos = new IncluirServico($this->db);
	$data = $projetos->get($request->getParams());
	return $response->withJson($data);
});

$app->get('/incluir-os/get/{id}', function (Request $request, Response $response, array $args) {
	$projetos = new IncluirServico($this->db);
	$data = $projetos->get_by_id($args['id'], $request->getParams());
	return $response->withJson($data);
});

$app->post('/incluir-os/delete', function (Request $request, Response $response, array $args) {
	$projetos = new IncluirServico($this->db);
	$data = $projetos->delete($request->getParams());
	return $response->withJson($data);
});

$app->post('/incluir-os/update', function (Request $request, Response $response, array $args) {
	$projetos = new IncluirServico($this->db);
	$data = $projetos->update($request->getParams());
	return $response->withJson($data);
});

$app->get('/incluir-os/search', function (Request $request, Response $response, array $args) {
	$projetos = new IncluirServico($this->db);
	$data = $projetos->search($request->getParams());
	return $response->withJson($data);
});

$app->post('/incluir-os/changestatus', function (Request $request, Response $response, array $args) {
	$projetos = new IncluirServico($this->db);
	$data = $projetos->change_status($request->getParams());
	return $response->withJson($data);
});


?>