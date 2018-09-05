<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Categorias;
use Classes\Utilities;

$app->post('/categorias/insert', function (Request $request, Response $response, array $args) {

	$categorias = new Categorias($this->db);
	$data = $categorias->insert($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data
		),
		'log_modulo' => 'categorias',
		'log_descricao' => 'Inserção de categoria',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'insert',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => ($data['result'] ? $data['id'] : '0') 
	));

	return $response->withJson($data);
});

// Update de categoria
$app->post('/categorias/update', function (Request $request, Response $response, array $args) {

	$params = $request->getParams();

	$categorias = new Categorias($this->db);
	$data = $categorias->update($params);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $params,
			'response' => $data
		),
		'log_modulo' => 'categorias',
		'log_descricao' => 'Update de categoria',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'update',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => (isset($params['id']) ? $params['id'] : '0') 
	));

	return $response->withJson($data);
});

// Seleção de todas as categorias
$app->get('/categorias/get', function (Request $request, Response $response, array $args) {

	$categorias = new Categorias($this->db);
	$data = $categorias->get($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data['config']
		),
		'log_modulo' => 'categorias',
		'log_descricao' => 'Seleção de todas as categorias',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get',
		'log_result' => true,
		'id_user' => $this->user_request['id'],
		'id_target' => ($data['result'] ? $data['id'] : '0')
	));

	return $response->withJson($data);
});

// Remoção de categoria
$app->post('/categorias/delete', function (Request $request, Response $response, array $args) {

	$params = $request->getParams();

	$categorias = new Categorias($this->db);
	$data = $categorias->delete($params);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $params,
			'response' => $data
		),
		'log_modulo' => 'categorias',
		'log_descricao' => 'Remoção de categoria',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'delete',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => (isset($params['id']) ? $params['id'] : '0') 
	));

	return $response->withJson($data);
});

// Seleção de categoria por ID
$app->get('/categorias/get/{id}', function (Request $request, Response $response, array $args) {
	$categorias = new Categorias($this->db);
	$data = $categorias->get_by_id($args['id'], $request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $args,
			'response' => $data
		),
		'log_modulo' => 'categorias',
		'log_descricao' => 'Seleção de categoria por ID',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get/id',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => $args['id']
	));

	return $response->withJson($data);
});

?>