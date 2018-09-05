<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Empresas;
use Classes\Utilities;

// Inserção de empresa
$app->post('/empresas/insert', function (Request $request, Response $response, array $args) {
	$empresas = new Empresas($this->db);
	$data = $empresas->insert($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data
		),
		'log_modulo' => 'empresas',
		'log_descricao' => 'Inserção de empresa',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'insert',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => ($data['result'] ? $data['id'] : '0')
	));

	return $response->withJson($data);
});

// Seleção de todas as empresas
$app->get('/empresas/get', function (Request $request, Response $response, array $args) {
	$empresas = new Empresas($this->db);
	$data = $empresas->get($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data['config']
		),
		'log_modulo' => 'empresas',
		'log_descricao' => 'Seleção de todas as empresas',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get',
		'log_result' => true,
		'id_user' => $this->user_request['id'],
		'id_target' => '0'
	));

	return $response->withJson($data);
});

// Seleção de empresa por ID
$app->get('/empresas/get/{id}', function (Request $request, Response $response, array $args) {

	$empresas = new Empresas($this->db);
	$data = $empresas->get_by_id($args['id']);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $args,
			'response' => $data
		),
		'log_modulo' => 'empresas',
		'log_descricao' => 'Seleção de empresa por ID',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get/id',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => $args['id']
	));

	return $response->withJson($data);
});

// Remoção de empresa por ID
$app->post('/empresas/delete', function (Request $request, Response $response, array $args) {

	$params = $request->getParams();

	$empresas = new Empresas($this->db);
	$data = $empresas->delete($params);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data
		),
		'log_modulo' => 'empresas',
		'log_descricao' => 'Remoção de empresa por ID',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'delete',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => (isset($params['id']) ? $params['id'] : '0')
	));

	return $response->withJson($data);
});

// Update de empresa
$app->post('/empresas/update', function (Request $request, Response $response, array $args) {

	$params = $request->getParams();

	$empresas = new Empresas($this->db);
	$data = $empresas->update($params);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $params,
			'response' => $data
		),
		'log_modulo' => 'empresas',
		'log_descricao' => 'Update de empresa',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'update',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => $params['id']
	));

	return $response->withJson($data);
});




?>