<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\IncluirServico;
use Classes\Utilities;

$app->post('/incluir-servico/insert', function (Request $request, Response $response, array $args) {
	$incluir-servico = new IncluirServico($this->db);
	$data = $incluir-servico->insert($request->getParams());
	return $response->withJson($data);
});

$app->get('/incluir-servico/get', function (Request $request, Response $response, array $args) {
	$projetos = new IncluirServico($this->db);
	$data = $projetos->get($request->getParams());
	return $response->withJson($data);
});

$app->get('/incluir-servico/get/{id}', function (Request $request, Response $response, array $args) {
	$projetos = new IncluirServico($this->db);
	$data = $projetos->get_by_id($args['id'], $request->getParams());
	return $response->withJson($data);
});

$app->post('/incluir-servico/delete', function (Request $request, Response $response, array $args) {
	$projetos = new IncluirServico($this->db);
	$data = $projetos->delete($request->getParams());
	return $response->withJson($data);
});

$app->post('/incluir-servico/update', function (Request $request, Response $response, array $args) {
	$projetos = new IncluirServico($this->db);
	$data = $projetos->update($request->getParams());
	return $response->withJson($data);
});

$app->get('/incluir-servico/search', function (Request $request, Response $response, array $args) {
	$projetos = new IncluirServico($this->db);
	$data = $projetos->search($request->getParams());
	return $response->withJson($data);
});

$app->post('/incluir-servico/changestatus', function (Request $request, Response $response, array $args) {
	$projetos = new IncluirServico($this->db);
	$data = $projetos->change_status($request->getParams());
	return $response->withJson($data);
});


?>