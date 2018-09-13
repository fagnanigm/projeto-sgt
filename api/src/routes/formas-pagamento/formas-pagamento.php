<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\FormasPagamento;
use Classes\Utilities;

// Inserção de forma de pagamento
$app->post('/formas-pagamento/insert', function (Request $request, Response $response, array $args) {

	$formasPagamento = new FormasPagamento($this->db);
	$data = $formasPagamento->insert($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data
		),
		'log_modulo' => 'formas-pagamento',
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
$app->get('/formas-pagamento/get', function (Request $request, Response $response, array $args) {

	$formasPagamento = new FormasPagamento($this->db);
	$data = $formasPagamento->get($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data['config']
		),
		'log_modulo' => 'formas-pagamento',
		'log_descricao' => 'Seleção de todas as formas de pagamento',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get',
		'log_result' => true,
		'id_user' => $this->user_request['id'],
		'id_target' => '0' 
	));

	return $response->withJson($data);
});


// Seleção de forma de pagamento por ID
$app->get('/formas-pagamento/get/{id}', function (Request $request, Response $response, array $args) {

	$formasPagamento = new FormasPagamento($this->db);
	$data = $formasPagamento->get_by_id($args['id'], $request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $args,
			'response' => $data
		),
		'log_modulo' => 'formas-pagamento',
		'log_descricao' => 'Seleção de forma de pagamento por ID',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get/id',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => $args['id']
	));

	return $response->withJson($data);
});


// Remoção de forma de pagamento
$app->post('/formas-pagamento/delete', function (Request $request, Response $response, array $args) {

	$params = $request->getParams();

	$formasPagamento = new FormasPagamento($this->db);
	$data = $formasPagamento->delete($params);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $params,
			'response' => $data
		),
		'log_modulo' => 'formas-pagamento',
		'log_descricao' => 'Remoção de forma de pagamento',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'delete',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => (isset($params['id']) ? $params['id'] : '0' )
	));

	return $response->withJson($data);
});

// Update de forma de pagamento
$app->post('/formas-pagamento/update', function (Request $request, Response $response, array $args) {

	$params = $request->getParams();

	$formasPagamento = new FormasPagamento($this->db);
	$data = $formasPagamento->update($params);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $params,
			'response' => $data
		),
		'log_modulo' => 'formas-pagamento',
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