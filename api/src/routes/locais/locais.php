<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Locais;
use Classes\Utilities;


/**
	@SWG\Post(

		path="/locais/insert",
		summary="Inserção de locais",
		tags={"Locais"},
		description="Essa função realiza o cadastro dos locais na base de dados. Os locais são endereços físicos que são frequentados com frequência, sendo utilizados nos locais de coleta e locais de entrega das mercadorias.",
		operationId="locais-insert",
		produces={"application/json"},

		@SWG\Parameter(
			name="id_author",
			in="query",
			description="ID do usuário que criou o local",
			required=true,
			type="integer"
		),

		@SWG\Parameter(
			name="local_nome",
			in="query",
			description="Nome do local",
			required=true,
			type="string"
		),
		@SWG\Parameter(
			name="local_apelido",
			in="query",
			description="Apelido do local",
			type="string"
		),
		@SWG\Parameter(
			name="local_logradouro",
			in="query",
			description="Nome da rua / logradouro",
			required=true,
			type="string"
		),
		@SWG\Parameter(
			name="local_numero",
			in="query",
			description="Número do endereço",
			required=true,
			type="string"
		),
		@SWG\Parameter(
			name="local_complemento",
			in="query",
			description="Complemento do endereço",
			required=false,
			type="string"
		),
		@SWG\Parameter(
			name="local_bairro",
			in="query",
			description="Bairro do endereço",
			required=true,
			type="string"
		),
		@SWG\Parameter(
			name="local_cidade",
			in="query",
			description="Cidade do endereço",
			required=true,
			type="string"
		),
		@SWG\Parameter(
			name="local_cidade_ibge",
			in="query",
			description="Código IBGE da cidade",
			required=false,
			type="string"
		),
		@SWG\Parameter(
			name="local_estado",
			in="query",
			description="Estado do endereço",
			required=true,
			type="string"
		),
		@SWG\Parameter(
			name="local_estado_ibge",
			in="query",
			description="Código IBGE do estado",
			required=false,
			type="string"
		),
		@SWG\Parameter(
			name="local_pais",
			in="query",
			description="País do endereço",
			required=false,
			type="string"
		),
		@SWG\Parameter(
			name="local_pais_ibge",
			in="query",
			description="Código IBGE do país",
			required=false,
			type="string"
		),
		@SWG\Parameter(
			name="local_cep",
			in="query",
			description="CEP do endereço",
			required=false,
			type="string"
		),
		@SWG\Parameter(
			name="local_cnpj",
			in="query",
			description="CNPJ do cliente associado ao local",
			required=false,
			type="string"
		),
		@SWG\Parameter(
			name="local_exterior",
			in="query",
			description="Campo informativo se o endereço é no exterior",
			required=false,
			type="string"
		),
		@SWG\Parameter(
			name="local_observacao",
			in="query",
			description="Campo livre para observações do local",
			required=false,
			type="string"
		),
		@SWG\Parameter(
			name="create_time",
			in="query",
			description="Data de cadastro",
			required=false,
			type="string",
			format="date-time"
		),
		@SWG\Parameter(
			name="active",
			in="query",
			description="Campo para determinar se está ativo / inativo",
			required=false,
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

/**
	@SWG\Get(

		path="/locais/get",
		summary="Seleção de locais",
		tags={"Locais"},
		description="Essa função realiza a seleção dos locais de várias formas, sendo elas: paginação, listagem completa ou pesquisa.",
		operationId="locais-get",
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
			name="local_term",
			in="query",
			description="Termo para pesquisa de local pelo nome ou pelo apelido, utilizado apenas para pesquisa. ",
			type="integer"
		),
		@SWG\Parameter(
			name="getall",
			in="query",
			description="Caso este parâmetro seja informado, a API retornará com a lista completa dos locais.",
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

/**
	@SWG\Get(

		path="/locais/get/{id}",
		summary="Seleção de local pelo ID",
		tags={"Locais"},
		description="Essa função realiza a seleção do local pelo ID.",
		operationId="locais-get-by-id",
		produces={"application/json"},

		@SWG\Parameter(
			name="id",
			in="path",
			description="ID do local.",
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

/**
	@SWG\Post(

		path="/locais/delete",
		summary="Remoção do local pelo ID",
		tags={"Locais"},
		description="Essa função realiza a remoção do local pelo ID.",
		operationId="locais-delete",
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

/**
	@SWG\Post(

		path="/locais/update",
		summary="Atualização do local pelo ID",
		tags={"Locais"},
		description="Essa função realiza a atualização do local pelo ID.",
		operationId="locais-update",
		produces={"application/json"},

		@SWG\Parameter(
			name="id",
			in="query",
			description="ID do local.",
			type="integer",
			required=true,
		),
		@SWG\Parameter(
			name="local_nome",
			in="query",
			description="Nome do local",
			required=false,
			type="string"
		),
		@SWG\Parameter(
			name="local_apelido",
			in="query",
			description="Apelido do local",
			type="string"
		),
		@SWG\Parameter(
			name="local_logradouro",
			in="query",
			description="Nome da rua / logradouro",
			required=false,
			type="string"
		),
		@SWG\Parameter(
			name="local_numero",
			in="query",
			description="Número do endereço",
			required=false,
			type="string"
		),
		@SWG\Parameter(
			name="local_complemento",
			in="query",
			description="Complemento do endereço",
			required=false,
			type="string"
		),
		@SWG\Parameter(
			name="local_bairro",
			in="query",
			description="Bairro do endereço",
			required=false,
			type="string"
		),
		@SWG\Parameter(
			name="local_cidade",
			in="query",
			description="Cidade do endereço",
			required=false,
			type="string"
		),
		@SWG\Parameter(
			name="local_cidade_ibge",
			in="query",
			description="Código IBGE da cidade",
			required=false,
			type="string"
		),
		@SWG\Parameter(
			name="local_estado",
			in="query",
			description="Estado do endereço",
			required=false,
			type="string"
		),
		@SWG\Parameter(
			name="local_estado_ibge",
			in="query",
			description="Código IBGE do estado",
			required=false,
			type="string"
		),
		@SWG\Parameter(
			name="local_pais",
			in="query",
			description="País do endereço",
			required=false,
			type="string"
		),
		@SWG\Parameter(
			name="local_pais_ibge",
			in="query",
			description="Código IBGE do país",
			required=false,
			type="string"
		),
		@SWG\Parameter(
			name="local_cep",
			in="query",
			description="CEP do endereço",
			required=false,
			type="string"
		),
		@SWG\Parameter(
			name="local_cnpj",
			in="query",
			description="CNPJ do cliente associado ao local",
			required=false,
			type="string"
		),
		@SWG\Parameter(
			name="local_exterior",
			in="query",
			description="Campo informativo se o endereço é no exterior",
			required=false,
			type="string"
		),
		@SWG\Parameter(
			name="local_observacao",
			in="query",
			description="Campo livre para observações do local",
			required=false,
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


/**
	@SWG\Get(

		path="/locais/search",
		summary="Realiza pesquisa do local por um termo",
		tags={"Locais"},
		description="Realiza a pesquisa de local pelo apelido, nome e CNPJ por um termo qualquer.",
		operationId="locais-search",
		produces={"application/json"},
		security={
			{"Bearer":{}}
		},

		@SWG\Parameter(
			name="term",
			in="query",
			description="Termo para ser pesquisado.",
			type="string",
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