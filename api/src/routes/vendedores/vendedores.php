<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Vendedores;
use Classes\Utilities;


/**
	@SWG\Post(

		path="/vendedores/insert",
		summary="Inserção de vendedores",
		tags={"Vendedores"},
		description="Essa função realiza o cadastro dos locais na base de dados. Os locais são endereços físicos que são frequentados com frequência, sendo utilizados nos locais de coleta e locais de entrega das mercadorias.",
		operationId="vendedores-insert",
		produces={"application/json"},
		security={
			{"Bearer":{}}
		},

		@SWG\Parameter(
			name="body",
			in="body",
			required=true,
			@SWG\Schema(
				@SWG\Property(
					property="id_author",
					type="integer"
				),
				@SWG\Property(
					property="vendedor_nome",
					type="string"
				),
				@SWG\Property(
					property="vendedor_telefone",
					type="string"
				),
				@SWG\Property(
					property="vendedor_email",
					type="string"
				),
				@SWG\Property(
					property="vendedor_comissao",
					type="string"
				)
			
			)
		),

		@SWG\Response(
			response=200,
			description="Requisição realizada com sucesso",
		),

		@SWG\Response(
			response=401,
			description="Não autorizado",
		),

		@SWG\Response(
			response="400",
			description="Falha na requisição",
		),
		deprecated=false
	)

**/

// Inserção de vendedor
$app->post('/vendedores/insert', function (Request $request, Response $response, array $args) {

	$vendedores = new Vendedores($this->db);
	$data = $vendedores->insert($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data
		),
		'log_modulo' => 'vendedores',
		'log_descricao' => 'Inserção de vendedor',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'insert',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => ($data['result'] ? $data['id'] : '0') 
	));

	return $response->withJson($data);
});

/**
	@SWG\Post(

		path="/vendedores/update",
		summary="Atualização de vendedores",
		tags={"Vendedores"},
		description="Essa função faz a atualização dos vendedores.",
		operationId="vendedores-update",
		produces={"application/json"},
		security={
			{"Bearer":{}}
		},

		@SWG\Parameter(
			name="body",
			in="body",
			required=true,
			@SWG\Schema(
				@SWG\Property(
					property="id",
					type="integer"
				),
				@SWG\Property(
					property="vendedor_nome",
					type="string"
				),
				@SWG\Property(
					property="vendedor_telefone",
					type="string"
				),
				@SWG\Property(
					property="vendedor_email",
					type="string"
				),
				@SWG\Property(
					property="vendedor_comissao",
					type="string"
				)
			
			)
		),

		@SWG\Response(
			response=200,
			description="Requisição realizada com sucesso",
		),

		@SWG\Response(
			response=401,
			description="Não autorizado",
		),

		@SWG\Response(
			response="400",
			description="Falha na requisição",
		),
		deprecated=false
	)

**/

// Update de vendedor
$app->post('/vendedores/update', function (Request $request, Response $response, array $args) {

	$params = $request->getParams();

	$vendedores = new Vendedores($this->db);
	$data = $vendedores->update($params);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $params,
			'response' => $data
		),
		'log_modulo' => 'vendedores',
		'log_descricao' => 'Update de vendedor',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'update',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => (isset($params['id']) ? $params['id'] : '0') 
	));

	return $response->withJson($data);
});

/**
	@SWG\Get(

		path="/vendedores/get",
		summary="Seleção de vendedores",
		tags={"Vendedores"},
		description="Essa função realiza a seleção dos vendedores de várias formas, sendo elas: paginação, listagem completa ou pesquisa.",
		operationId="vendedores-get",
		produces={"application/json"},
		security={
			{"Bearer":{}}
		},

		@SWG\Parameter(
			name="current_page",
			in="query",
			description="Número da página de seleção, caso não informado, traz a primeira página.",
			type="string"
		),
		@SWG\Parameter(
			name="vendedor_term",
			in="query",
			description="Termo para pesquisa de vendedor pelo nome ou pelo e-mail, utilizado apenas para pesquisa. ",
			type="string"
		),
		@SWG\Parameter(
			name="getall",
			in="query",
			description="Caso este parâmetro seja informado, a API retornará com a lista completa dos vendedores.",
			type="string"
		),
		
		@SWG\Response(
			response=200,
			description="Requisição realizada com sucesso",
		),

		@SWG\Response(
			response=401,
			description="Não autorizado",
		),

		@SWG\Response(
			response="400",
			description="Falha na requisição",
		),
		deprecated=false
	)

**/

// Seleção de todas os vendedores
$app->get('/vendedores/get', function (Request $request, Response $response, array $args) {

	$vendedores = new Vendedores($this->db);
	$data = $vendedores->get($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data['config']
		),
		'log_modulo' => 'vendedores',
		'log_descricao' => 'Seleção de todas os vendedores',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get',
		'log_result' => true,
		'id_user' => $this->user_request['id'],
		'id_target' => ($data['result'] ? $data['id'] : '0')
	));

	return $response->withJson($data);
});


/**
	@SWG\Post(

		path="/vendedores/delete",
		summary="Remoção do vendedor pelo ID",
		tags={"Vendedores"},
		description="Essa função realiza a remoção do vendedor pelo ID.",
		operationId="vendedores-delete",
		produces={"application/json"},

		@SWG\Parameter(
			name="body",
			in="body",
			required=true,
			@SWG\Schema(
				@SWG\Property(
					property="id",
					type="integer",
				)
			)
		),
		
		@SWG\Response(
			response=200,
			description="Requisição realizada com sucesso",
		),

		@SWG\Response(
			response=401,
			description="Não autorizado",
		),

		@SWG\Response(
			response="400",
			description="Falha na requisição",
		),
		deprecated=false
	)

**/

// Remoção de vendedor
$app->post('/vendedores/delete', function (Request $request, Response $response, array $args) {

	$params = $request->getParams();

	$vendedores = new Vendedores($this->db);
	$data = $vendedores->delete($params);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $params,
			'response' => $data
		),
		'log_modulo' => 'vendedores',
		'log_descricao' => 'Remoção de vendedor',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'delete',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => (isset($params['id']) ? $params['id'] : '0') 
	));

	return $response->withJson($data);
});


/**
	@SWG\Get(

		path="/vendedores/get/{id}",
		summary="Seleção de vendedor pelo ID",
		tags={"Vendedores"},
		description="Essa função realiza a seleção do vendedor pelo ID.",
		operationId="vendedores-get-by-id",
		produces={"application/json"},

		@SWG\Parameter(
			name="id",
			in="path",
			description="ID do vendedor.",
			type="integer",
			required=true
		),
		
		@SWG\Response(
			response=200,
			description="Requisição realizada com sucesso",
		),

		@SWG\Response(
			response=401,
			description="Não autorizado",
		),

		@SWG\Response(
			response="400",
			description="Falha na requisição",
		),
		deprecated=false
	)

**/

// Seleção de vendedor por ID
$app->get('/vendedores/get/{id}', function (Request $request, Response $response, array $args) {
	$vendedores = new Vendedores($this->db);
	$data = $vendedores->get_by_id($args['id'], $request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $args,
			'response' => $data
		),
		'log_modulo' => 'vendedores',
		'log_descricao' => 'Seleção de vendedor por ID',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get/id',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => $args['id']
	));

	return $response->withJson($data);
});

?>