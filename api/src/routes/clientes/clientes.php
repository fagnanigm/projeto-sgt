<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Clientes;
use Classes\Utilities;

// Inserção de cliente
$app->post('/clientes/insert', function (Request $request, Response $response, array $args) {

	$clientes = new Clientes($this->db);
	$data = $clientes->insert($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data
		),
		'log_modulo' => 'clientes',
		'log_descricao' => 'Inserção de cliente',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'insert',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => ($data['result'] ? $data['id'] : '0')
	));

	return $response->withJson($data);
});

// Seleção de todos os clientes
$app->get('/clientes/get', function (Request $request, Response $response, array $args) {

	$clientes = new Clientes($this->db, 10);
	$data = $clientes->get($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data['config']
		),
		'log_modulo' => 'clientes',
		'log_descricao' => 'Seleção de todos os clientes',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get',
		'log_result' => true,
		'id_user' => $this->user_request['id'],
		'id_target' => '0'
	));

	return $response->withJson($data);
});

// Remoção de cliente
$app->post('/clientes/delete', function (Request $request, Response $response, array $args) {

	$params = $request->getParams();

	$clientes = new Clientes($this->db);
	$data = $clientes->delete($params);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data
		),
		'log_modulo' => 'clientes',
		'log_descricao' => 'Remoção de cliente',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'delete',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => (isset($params['id']) ? $params['id'] : '0') 
	));

	return $response->withJson($data);
});

// Seleção de clientes por ID
$app->get('/clientes/get/{id}', function (Request $request, Response $response, array $args) {

	$clientes = new Clientes($this->db);
	$data = $clientes->get_by_id($args['id'], $request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $args,
			'response' => $data
		),
		'log_modulo' => 'clientes',
		'log_descricao' => 'Seleção de cliente por ID',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get/id',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => $args['id']
	));

	return $response->withJson($data);
});

// Importar do OMIE
$app->post('/clientes/importOmie', function (Request $request, Response $response, array $args) {

	$clientes = new Clientes($this->db);
	$data = $clientes->importOmie($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $args,
			'response' => $data['config']
		),
		'log_modulo' => 'clientes',
		'log_descricao' => 'Importar do OMIE',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'import',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => '0'
	));

	return $response->withJson($data);
});

// 
$app->get('/clientes/import/getempresas', function (Request $request, Response $response, array $args) {

	$clientes = new Clientes($this->db);
	$data = $clientes->getEmpresas();

	return $response->withJson($data);
});


// Procura de clientes
$app->get('/clientes/search', function (Request $request, Response $response, array $args) {

	$clientes = new Clientes($this->db);
	$data = $clientes->search($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $args,
			'response' => $data
		),
		'log_modulo' => 'clientes',
		'log_descricao' => 'Procura de clientes',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'search',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => '0'
	));

	return $response->withJson($data);
});



/*

$app->post('/users/update', function (Request $request, Response $response, array $args) {
	$user = new User($this->db);
	$data = $user->update($request->getParams());
	return $response->withJson($data);
});

*/



?>