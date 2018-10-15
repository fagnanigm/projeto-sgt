<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Motoristas;
use Classes\Utilities;

// Inserção de motorista
$app->post('/motoristas/insert', function (Request $request, Response $response, array $args) {

	$motoristas = new Motoristas($this->db);
	$data = $motoristas->insert($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data
		),
		'log_modulo' => 'motoristas',
		'log_descricao' => 'Inserção de motorista',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'insert',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => ($data['result'] ? $data['id'] : '0')
	));

	return $response->withJson($data);
});

// Seleção de todos os motoristas
$app->get('/motoristas/get', function (Request $request, Response $response, array $args) {
	$motoristas = new Motoristas($this->db);
	$data = $motoristas->get($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data['config']
		),
		'log_modulo' => 'motoristas',
		'log_descricao' => 'Seleção de todos os motoristas',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get',
		'log_result' => true,
		'id_user' => $this->user_request['id'],
		'id_target' => '0' 
	));

	return $response->withJson($data);
});

// Seleção de motorista por ID
$app->get('/motoristas/get/{id}', function (Request $request, Response $response, array $args) {
	$motoristas = new Motoristas($this->db);
	$data = $motoristas->get_by_id($args['id'], $request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $args,
			'response' => $data
		),
		'log_modulo' => 'motoristas',
		'log_descricao' => 'Seleção de motorista por ID',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get/id',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => $args['id']
	));

	return $response->withJson($data);
});

// Remoção de motorista
$app->post('/motoristas/delete', function (Request $request, Response $response, array $args) {

	$params = $request->getParams();

	$motoristas = new Motoristas($this->db);
	$data = $motoristas->delete($params);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $params,
			'response' => $data
		),
		'log_modulo' => 'motoristas',
		'log_descricao' => 'Remoção de motorista',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'delete',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => (isset($params['id']) ? $params['id'] : '0' )
	));

	return $response->withJson($data);
});

// Update de motorista
$app->post('/motoristas/update', function (Request $request, Response $response, array $args) {

	$params = $request->getParams();

	$motoristas = new Motoristas($this->db);
	$data = $motoristas->update($params);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $params,
			'response' => $data
		),
		'log_modulo' => 'motoristas',
		'log_descricao' => 'Update de motorista',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'update',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => (isset($params['id']) ? $params['id'] : '0' )
	));

	return $response->withJson($data);
});

// Procura de motoristas
$app->get('/motoristas/search', function (Request $request, Response $response, array $args) {
	$motoristas = new Motoristas($this->db);
	$data = $motoristas->search($request->getParams());
	return $response->withJson($data);
});

?>