<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\User;
use Classes\Utilities;

// Seleção de todos os usuários
$app->get('/users/get', function (Request $request, Response $response, array $args) {
	
	$user = new User($this->db);
	$data = $user->get($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data['config']
		),
		'log_modulo' => 'users',
		'log_descricao' => 'Seleção de todos os usuários',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get',
		'log_result' => true,
		'id_user' => $this->user_request['id'],
		'id_target' => '0'
	));

	return $response->withJson($data);
});

// Inserção de usuário
$app->post('/users/insert', function (Request $request, Response $response, array $args) {
	$user = new User($this->db);
	$data = $user->insert($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data
		),
		'log_modulo' => 'users',
		'log_descricao' => 'Inserção de usuário',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'insert',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => ($data['result'] ? $data['id'] : '0' )
	));

	return $response->withJson($data);
});

// 
$app->post('/users/delete', function (Request $request, Response $response, array $args) {

	$params = $request->getParams();

	$user = new User($this->db);
	$data = $user->delete($params);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $params,
			'response' => $data
		),
		'log_modulo' => 'users',
		'log_descricao' => 'Remoção de usuário',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'delete',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => (isset($params['id']) ? $params['id'] : '0') 
	));

	return $response->withJson($data);
});

// Update de usuário
$app->post('/users/update', function (Request $request, Response $response, array $args) {

	$params = $request->getParams();

	$user = new User($this->db);
	$data = $user->update($params);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $params,
			'response' => $data
		),
		'log_modulo' => 'users',
		'log_descricao' => 'Update de usuário',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'update',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => (isset($params['id']) ? $params['id'] : '0') 
	));

	return $response->withJson($data);
});

// Requisição de login 
$app->post('/users/login', function (Request $request, Response $response, array $args) {

	$user = new User($this->db);
	$data = $user->login($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data
		),
		'log_modulo' => 'users',
		'log_descricao' => 'Requisição de login',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'login',
		'log_result' => $data['result'],
		'id_user' => ($data['result'] ? $data['user']['id'] : 0),
		'id_target' => '0'
	));

	return $response->withJson($data);
});

// Seleção de usuário por ID
$app->get('/users/get/{id}', function (Request $request, Response $response, array $args) {

	$user = new User($this->db);
	$data = $user->get_by_id($args['id']);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $args,
			'response' => $data
		),
		'log_modulo' => 'users',
		'log_descricao' => 'Seleção de usuário por ID',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get/id',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => $args['id']
	));

	return $response->withJson($data);
});


// Seleção de usuário por ID
$app->get('/users/persist/{id}', function (Request $request, Response $response, array $args) {

	$user = new User($this->db);
	$data = $user->get_by_id($args['id']);

	return $response->withJson($data);
});

?>