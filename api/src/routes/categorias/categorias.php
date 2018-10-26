<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Categorias;
use Classes\Utilities;

/**
	@SWG\Post(

		path="/categorias/insert",
		summary="Inserção de categorias",
		tags={"Categorias"},
		description="Essa função realiza o cadastro das categorias na base de dados. As categorias são termos utilizados para classificação do serviço prestado, exemplo: transportes, locações de equipamentos, etc.",
		operationId="categorias-insert",
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
					property="cat_name",
					type="string"
				),
				@SWG\Property(
					property="cat_descricao",
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

/**
	@SWG\Post(

		path="/categorias/update",
		summary="Atualização de categorias",
		tags={"Categorias"},
		description="Essa função faz a atualização das categorias.",
		operationId="categorias-update",
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
					property="cat_name",
					type="string"
				),
				@SWG\Property(
					property="cat_descricao",
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

/**
	@SWG\Get(

		path="/categorias/get",
		summary="Seleção de categorias",
		tags={"Categorias"},
		description="Essa função realiza a seleção das categorias de várias formas, sendo elas: paginação, listagem completa ou pesquisa.",
		operationId="categorias-get",
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
			name="cat_name",
			in="query",
			description="Termo para pesquisa da categoria pelo nome, utilizado apenas para pesquisa. ",
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

/**
	@SWG\Post(

		path="/categorias/delete",
		summary="Remoção da categoria pelo ID",
		tags={"Categorias"},
		description="Essa função realiza a remoção da categoria pelo ID.",
		operationId="categorias-delete",
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

/**
	@SWG\Get(

		path="/categorias/get/{id}",
		summary="Seleção de categoria pelo ID",
		tags={"Categorias"},
		description="Essa função realiza a seleção da categoria pelo ID.",
		operationId="categorias-get-by-id",
		produces={"application/json"},

		@SWG\Parameter(
			name="id",
			in="path",
			description="ID da categoria.",
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