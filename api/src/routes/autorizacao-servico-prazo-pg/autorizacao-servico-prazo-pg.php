<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\AutorizacaoServicoPrazoPg;
use Classes\Utilities;

// Inserção de autorizacao de servico prazo pagamento
$app->post('/autorizacao-servico-prazo-pg/insert', function (Request $request, Response $response, array $args) {

	$as_servico_pg = new AutorizacaoServicoPrazoPg($this->db);
	$data = $as_servico_pg->insert($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data
		),
		'log_modulo' => 'autorizacao-servico-prazo-pg',
		'log_descricao' => 'Inserção de forma de pagamento',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'insert',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => ($data['result'] ? $data['id'] : '0')
	));

	return $response->withJson($data);
});

// Seleção de todas as formas de pagamento
$app->get('/autorizacao-servico-prazo-pg/get', function (Request $request, Response $response, array $args) {

	$as_servico_pg = new AutorizacaoServicoPrazoPg($this->db);
	$data = $as_servico_pg->get($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data['config']
		),
		'log_modulo' => 'autorizacao-servico-prazo-pg',
	//	'log_descricao' => 'Seleção de todas as formas de pagamento',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get',
		'log_result' => true,
		'id_user' => $this->user_request['id'],
		'id_target' => '0' 
	));

	return $response->withJson($data);
});


// Seleção de autorizacao de servico prazo pagamento por ID
$app->get('/autorizacao-servico-prazo-pg/get/{id}', function (Request $request, Response $response, array $args) {

	$as_servico_pg = new AutorizacaoServicoPrazoPg($this->db);
	$data = $as_servico_pg->get_by_id($args['id'], $request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $args,
			'response' => $data
		),
		'log_modulo' => 'autorizacao-servico-prazo-pg',
		'log_descricao' => 'Seleção de forma de pagamento por ID',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get/id',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => $args['id']
	));

	return $response->withJson($data);
});


// Remoção de autorizacao de servico prazo pagamento
$app->post('/autorizacao-servico-prazo-pg/delete', function (Request $request, Response $response, array $args) {

	$params = $request->getParams();

	$as_servico_pg = new AutorizacaoServicoPrazoPg($this->db);
	$data = $as_servico_pg->delete($params);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $params,
			'response' => $data
		),
		'log_modulo' => 'autorizacao-servico-prazo-pg',
		'log_descricao' => 'Remoção de forma de pagamento',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'delete',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => (isset($params['id']) ? $params['id'] : '0' )
	));

	return $response->withJson($data);
});

// Update de autorizacao de servico prazo pagamento
$app->post('/autorizacao-servico-prazo-pg/update', function (Request $request, Response $response, array $args) {

	$params = $request->getParams();

	$as_servico_pg = new AutorizacaoServicoPrazoPg($this->db);
	$data = $as_servico_pg->update($params);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $params,
			'response' => $data
		),
		'log_modulo' => 'autorizacao-servico-prazo-pg',
		'log_descricao' => 'Update de forma de pagamento',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'update',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => (isset($params['id']) ? $params['id'] : '0' )
	));

	return $response->withJson($data);
});

?>