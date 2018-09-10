<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Vendedores;
use Classes\Utilities;

// Inserção de vendedor
$app->post('/vendedores/insert', function (Request $request, Response $response, array $args) {

	$vendedores = new Vendedores($this->db);
	$data = $vendedores->insert($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data
		),
		'log_modulo' => 'vendedores',
		'log_descricao' => 'Inserção de vendedor',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'insert',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => ($data['result'] ? $data['id'] : '0') 
	));

	return $response->withJson($data);
});

// Update de vendedor
$app->post('/vendedores/update', function (Request $request, Response $response, array $args) {

	$params = $request->getParams();

	$vendedores = new Vendedores($this->db);
	$data = $vendedores->update($params);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $params,
			'response' => $data
		),
		'log_modulo' => 'vendedores',
		'log_descricao' => 'Update de vendedor',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'update',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => (isset($params['id']) ? $params['id'] : '0') 
	));

	return $response->withJson($data);
});

// Seleção de todas os vendedores
$app->get('/vendedores/get', function (Request $request, Response $response, array $args) {

	$vendedores = new Vendedores($this->db);
	$data = $vendedores->get($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data['config']
		),
		'log_modulo' => 'vendedores',
		'log_descricao' => 'Seleção de todas os vendedores',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get',
		'log_result' => true,
		'id_user' => $this->user_request['id'],
		'id_target' => ($data['result'] ? $data['id'] : '0')
	));

	return $response->withJson($data);
});

// Remoção de vendedor
$app->post('/vendedores/delete', function (Request $request, Response $response, array $args) {

	$params = $request->getParams();

	$vendedores = new Vendedores($this->db);
	$data = $vendedores->delete($params);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $params,
			'response' => $data
		),
		'log_modulo' => 'vendedores',
		'log_descricao' => 'Remoção de vendedor',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'delete',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => (isset($params['id']) ? $params['id'] : '0') 
	));

	return $response->withJson($data);
});

// Seleção de vendedor por ID
$app->get('/vendedores/get/{id}', function (Request $request, Response $response, array $args) {
	$vendedores = new Vendedores($this->db);
	$data = $vendedores->get_by_id($args['id'], $request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $args,
			'response' => $data
		),
		'log_modulo' => 'vendedores',
		'log_descricao' => 'Seleção de vendedor por ID',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get/id',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => $args['id']
	));

	return $response->withJson($data);
});

?>