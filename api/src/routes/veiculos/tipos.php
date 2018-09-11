<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\VeiculosTipos;
use Classes\Utilities;

// Inserção de tipo de veículo
$app->post('/veiculos/tipo/insert', function (Request $request, Response $response, array $args) {

	$veiculosTipos = new VeiculosTipos($this->db);
	$data = $veiculosTipos->insert($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data
		),
		'log_modulo' => 'veiculos/tipo',
		'log_descricao' => 'Inserção de tipo de veículo',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'insert',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => ($data['result'] ? $data['id'] : '0')
	));

	return $response->withJson($data);
});


// Seleção de todos os tipos de veiculos
$app->get('/veiculos/tipos/get', function (Request $request, Response $response, array $args) {

	$veiculosTipos = new VeiculosTipos($this->db);
	$data = $veiculosTipos->get($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data['config']
		),
		'log_modulo' => 'veiculos/tipo',
		'log_descricao' => 'Seleção de todos os tipos de veiculos',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get',
		'log_result' => true,
		'id_user' => $this->user_request['id'],
		'id_target' => '0' 
	));

	return $response->withJson($data);
});


// Seleção de tipo de veículo por ID
$app->get('/veiculos/tipos/get/{id}', function (Request $request, Response $response, array $args) {

	$veiculosTipos = new VeiculosTipos($this->db);
	$data = $veiculosTipos->get_by_id($args['id'], $request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $args,
			'response' => $data
		),
		'log_modulo' => 'veiculos/tipo',
		'log_descricao' => 'Seleção de tipo de veículo por ID',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get/id',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => $args['id']
	));

	return $response->withJson($data);
});


// Remoção de tipo de veículo
$app->post('/veiculos/tipos/delete', function (Request $request, Response $response, array $args) {

	$params = $request->getParams();

	$veiculosTipos = new VeiculosTipos($this->db);
	$data = $veiculosTipos->delete($params);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $params,
			'response' => $data
		),
		'log_modulo' => 'veiculos/tipo',
		'log_descricao' => 'Remoção de tipo de veículo',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'delete',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => (isset($params['id']) ? $params['id'] : '0' )
	));

	return $response->withJson($data);
});

// Update de tipo de veículo
$app->post('/veiculos/tipos/update', function (Request $request, Response $response, array $args) {

	$params = $request->getParams();

	$veiculosTipos = new VeiculosTipos($this->db);
	$data = $veiculosTipos->update($params);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $params,
			'response' => $data
		),
		'log_modulo' => 'veiculos/tipo',
		'log_descricao' => 'Update de tipo de veículo',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'update',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => (isset($params['id']) ? $params['id'] : '0' )
	));

	return $response->withJson($data);
});



?>