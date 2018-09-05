<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Locais;
use Classes\Utilities;

// Inserção de local
$app->post('/locais/insert', function (Request $request, Response $response, array $args) {
	$locais = new Locais($this->db);
	$data = $locais->insert($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data
		),
		'log_modulo' => 'locais',
		'log_descricao' => 'Inserção de local',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'insert',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => ($data['result'] ? $data['id'] : '0')
	));

	return $response->withJson($data);
});

// Seleção de todos os locais
$app->get('/locais/get', function (Request $request, Response $response, array $args) {

	$locais = new Locais($this->db);
	$data = $locais->get($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data['config']
		),
		'log_modulo' => 'locais',
		'log_descricao' => 'Seleção de todos os locais',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get',
		'log_result' => true,
		'id_user' => $this->user_request['id'],
		'id_target' => '0' 
	));

	return $response->withJson($data);
});

// Seleção de local por ID
$app->get('/locais/get/{id}', function (Request $request, Response $response, array $args) {

	$locais = new Locais($this->db);
	$data = $locais->get_by_id($args['id'], $request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $args,
			'response' => $data
		),
		'log_modulo' => 'locais',
		'log_descricao' => 'Seleção de local por ID',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get/id',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => $args['id']
	));

	return $response->withJson($data);
});

// Remoção de local
$app->post('/locais/delete', function (Request $request, Response $response, array $args) {

	$params = $request->getParams();

	$locais = new Locais($this->db);
	$data = $locais->delete($params);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $params,
			'response' => $data
		),
		'log_modulo' => 'locais',
		'log_descricao' => 'Remoção de local',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'delete',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => (isset($params['id']) ? $params['id'] : '0' )
	));

	return $response->withJson($data);
});

// Update de local
$app->post('/locais/update', function (Request $request, Response $response, array $args) {

	$params = $request->getParams();

	$locais = new Locais($this->db);
	$data = $locais->update($params);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $params,
			'response' => $data
		),
		'log_modulo' => 'locais',
		'log_descricao' => 'Update de local',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'update',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => (isset($params['id']) ? $params['id'] : '0' )
	));

	return $response->withJson($data);
});

// Procura de local
$app->get('/locais/search', function (Request $request, Response $response, array $args) {

	$locais = new Locais($this->db);
	$data = $locais->search($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data['result']
		),
		'log_modulo' => 'locais',
		'log_descricao' => 'Procura de local',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'search',
		'log_result' => true,
		'id_user' => $this->user_request['id'],
		'id_target' => '0'
	));

	return $response->withJson($data);
});




?>