<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\User;
use Classes\Utilities;

$app->get('/users/get', function (Request $request, Response $response, array $args) {
	$user = new User($this->db);
	$data = $user->get($request->getParams());
	return $response->withJson($data);
});

$app->post('/users/insert', function (Request $request, Response $response, array $args) {
	$user = new User($this->db);
	$data = $user->insert($request->getParams());
	return $response->withJson($data);
});

$app->post('/users/delete', function (Request $request, Response $response, array $args) {
	$user = new User($this->db);
	$data = $user->delete($request->getParams());
	return $response->withJson($data);
});

$app->post('/users/update', function (Request $request, Response $response, array $args) {
	$user = new User($this->db);
	$data = $user->update($request->getParams());
	return $response->withJson($data);
});

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
		'id_user' => ($data['result'] ? $data['user']['id'] : 0)
	));

	return $response->withJson($data);
});

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
		'id_user' => $this->user_request['id']
	));

	return $response->withJson($data);
});

?>