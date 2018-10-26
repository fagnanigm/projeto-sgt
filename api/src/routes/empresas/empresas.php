<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Empresas;
use Classes\Utilities;

/**
	@SWG\Post(

		path="/empresas/insert",
		summary="Inserção de empresas",
		tags={"Empresas"},
		description="Essa função realiza o cadastro das empresas na base de dados. As empresas podem ser matrizes e/ou filiais.",
		operationId="empresas-insert",
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
					property="empresa_name",
					type="string"
				),
				@SWG\Property(
					property="empresa_prefixo",
					type="string"
				),
				@SWG\Property(
					property="empresa_nome_fantasia",
					type="string"
				),
				@SWG\Property(
					property="empresa_razao_social",
					type="string"
				),
				@SWG\Property(
					property="empresa_name",
					type="string"
				),
				@SWG\Property(
					property="empresa_responsavel",
					type="string"
				),
				@SWG\Property(
					property="empresa_email",
					type="string"
				),
				@SWG\Property(
					property="empresa_phone_ddd",
					type="string"
				),
				@SWG\Property(
					property="empresa_phone",
					type="string"
				),
				@SWG\Property(
					property="empresa_cnpj",
					type="string"
				),
				@SWG\Property(
					property="empresa_ie",
					type="string"
				),
				@SWG\Property(
					property="empresa_im",
					type="string"
				),
				@SWG\Property(
					property="empresa_cep",
					type="string"
				),
				@SWG\Property(
					property="empresa_logradouro",
					type="string"
				),
				@SWG\Property(
					property="empresa_numero",
					type="string"
				),
				@SWG\Property(
					property="empresa_complemento",
					type="string"
				),
				@SWG\Property(
					property="empresa_bairro",
					type="string"
				),
				@SWG\Property(
					property="empresa_cidade",
					type="string"
				),
				@SWG\Property(
					property="empresa_estado",
					type="string"
				),
				@SWG\Property(
					property="empresa_app_key",
					type="string"
				),
				@SWG\Property(
					property="empresa_app_secret",
					type="string"
				),
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

// Inserção de empresa
$app->post('/empresas/insert', function (Request $request, Response $response, array $args) {
	$empresas = new Empresas($this->db);
	$data = $empresas->insert($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data
		),
		'log_modulo' => 'empresas',
		'log_descricao' => 'Inserção de empresa',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'insert',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => ($data['result'] ? $data['id'] : '0')
	));

	return $response->withJson($data);
});

/**
	@SWG\Get(

		path="/empresas/get",
		summary="Seleção de empresas",
		tags={"Empresas"},
		description="Essa função realiza a seleção das empresas de várias formas, sendo elas: paginação, listagem completa ou pesquisa.",
		operationId="empresas-get",
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
			name="empresa_term",
			in="query",
			description="Termo para pesquisa da empresa pela razão social, nome fantasia ou pelo nome, utilizado apenas para pesquisa. ",
			type="string"
		),
		@SWG\Parameter(
			name="getall",
			in="query",
			description="Caso este parâmetro seja informado, a API retornará com a lista completa das categorias.",
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

// Seleção de todas as empresas
$app->get('/empresas/get', function (Request $request, Response $response, array $args) {
	$empresas = new Empresas($this->db);
	$data = $empresas->get($request->getParams());

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data['config']
		),
		'log_modulo' => 'empresas',
		'log_descricao' => 'Seleção de todas as empresas',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get',
		'log_result' => true,
		'id_user' => $this->user_request['id'],
		'id_target' => '0'
	));

	return $response->withJson($data);
});

// Seleção de empresa por ID
$app->get('/empresas/get/{id}', function (Request $request, Response $response, array $args) {

	$empresas = new Empresas($this->db);
	$data = $empresas->get_by_id($args['id']);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $args,
			'response' => $data
		),
		'log_modulo' => 'empresas',
		'log_descricao' => 'Seleção de empresa por ID',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'get/id',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => $args['id']
	));

	return $response->withJson($data);
});

// Remoção de empresa por ID
$app->post('/empresas/delete', function (Request $request, Response $response, array $args) {

	$params = $request->getParams();

	$empresas = new Empresas($this->db);
	$data = $empresas->delete($params);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $request->getParams(),
			'response' => $data
		),
		'log_modulo' => 'empresas',
		'log_descricao' => 'Remoção de empresa por ID',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'delete',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => (isset($params['id']) ? $params['id'] : '0')
	));

	return $response->withJson($data);
});

// Update de empresa
$app->post('/empresas/update', function (Request $request, Response $response, array $args) {

	$params = $request->getParams();

	$empresas = new Empresas($this->db);
	$data = $empresas->update($params);

	// Registro de log
	$this->logs->insert(array(
		'log_content' => array( 
			'uri' => addslashes($request->getUri()),
			'request' => $params,
			'response' => $data
		),
		'log_modulo' => 'empresas',
		'log_descricao' => 'Update de empresa',
		'log_ip' => $request->getServerParam('REMOTE_ADDR'),
		'log_tool' => 'update',
		'log_result' => $data['result'],
		'id_user' => $this->user_request['id'],
		'id_target' => $params['id']
	));

	return $response->withJson($data);
});




?>