<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Localidades;
use Classes\Utilities;

// Localidades refresh
$app->post('/localidades/refresh', function (Request $request, Response $response, array $args) {

	$localidades = new Localidades($this->db);
	$data = $localidades->refresh($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data['result']
		),
		'log_modulo' => 'localidades',
		'log_descricao' => 'Localidades refresh',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'refresh',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => '0'
	));

	return $response->withJson($data);
});

// Seleção de todos os estados
$app->get('/localidades/get/ufs', function (Request $request, Response $response, array $args) {
	$localidades = new Localidades($this->db);
	$data = $localidades->getAllUfs();

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => count($data['results'])
		),
		'log_modulo' => 'localidades',
		'log_descricao' => 'Seleção de todos os estados',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get/ufs',
		'log_result' => true,
		'id_user' => $this->user_request['id'],
		'id_target' => '0' 
	));

	return $response->withJson($data);
});

// Seleção de estado por termo
$app->get('/localidades/get/uf/{term}', function (Request $request, Response $response, array $args) {
	$localidades = new Localidades($this->db);
	$data = $localidades->getUf($args['term']);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $args,
			'response' => $data
		),
		'log_modulo' => 'localidades',
		'log_descricao' => 'Seleção de estado por termo',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get/ufs',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => '0'
	));

	return $response->withJson($data);
});

// Seleção de municípios por termo
$app->get('/localidades/get/municipios/{term}', function (Request $request, Response $response, array $args) {
	$localidades = new Localidades($this->db);
	$data = $localidades->getMunicipios($args['term']);

	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $args,
			'response' => count($data['municipios'])
		),
		'log_modulo' => 'localidades',
		'log_descricao' => 'Seleção de municípios por termo',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get/municipios',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => '0'
	));

	return $response->withJson($data);
});

// Seleção de município por termo
$app->get('/localidades/get/municipio/{term}', function (Request $request, Response $response, array $args) {

	$args['term'] = utf8_encode($args['term']);

	$localidades = new Localidades($this->db);
	$data = $localidades->getMunicipio($args['term']);

	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $args,
			'response' => $data
		),
		'log_modulo' => 'localidades',
		'log_descricao' => 'Seleção de município por termo',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get/municipio',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => '0'
	));

	return $response->withJson($data);
});


?>