<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Veiculos;
use Classes\Utilities;

// Inserção de veiculo
$app->post('/veiculos/insert', function (Request $request, Response $response, array $args) {

	$veiculos = new Veiculos($this->db);
	$data = $veiculos->insert($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data
		),
		'log_modulo' => 'veiculos',
		'log_descricao' => 'Inserção de veiculo',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'insert',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => ($data['result'] ? $data['id'] : '0')
	));

	return $response->withJson($data);
});

// Seleção de todos os veiculos
$app->get('/veiculos/get', function (Request $request, Response $response, array $args) {

	$veiculos = new Veiculos($this->db);
	$data = $veiculos->get($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data['config']
		),
		'log_modulo' => 'veiculos',
		'log_descricao' => 'Seleção de todos os veiculos',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get',
		'log_result' => true,
		'id_user' => $this->user_request['id'],
		'id_target' => '0' 
	));

	return $response->withJson($data);
});

// Seleção de veículo por ID
$app->get('/veiculos/get/{id}', function (Request $request, Response $response, array $args) {

	$veiculos = new Veiculos($this->db);
	$data = $veiculos->get_by_id($args['id'], $request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $args,
			'response' => $data
		),
		'log_modulo' => 'veiculos',
		'log_descricao' => 'Seleção de veículo por ID',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get/id',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => $args['id']
	));

	return $response->withJson($data);
});

// Remoção de veículo
$app->post('/veiculos/delete', function (Request $request, Response $response, array $args) {

	$params = $request->getParams();

	$veiculos = new Veiculos($this->db);
	$data = $veiculos->delete($params);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $params,
			'response' => $data
		),
		'log_modulo' => 'veiculos',
		'log_descricao' => 'Remoção de veículo',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'delete',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => (isset($params['id']) ? $params['id'] : '0' )
	));

	return $response->withJson($data);
});

// Update de veículo
$app->post('/veiculos/update', function (Request $request, Response $response, array $args) {

	$params = $request->getParams();

	$veiculos = new Veiculos($this->db);
	$data = $veiculos->update($params);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $params,
			'response' => $data
		),
		'log_modulo' => 'veiculos',
		'log_descricao' => 'Update de veículo',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'update',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => (isset($params['id']) ? $params['id'] : '0' )
	));
	
	return $response->withJson($data);
});

// Procura de veículos
$app->get('/veiculos/search', function (Request $request, Response $response, array $args) {
	$veiculos = new Veiculos($this->db);
	$data = $veiculos->search($request->getParams());
	return $response->withJson($data);
});


?>