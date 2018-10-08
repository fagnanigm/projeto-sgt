<?php

namespace Classes;

class Projetos {

	private $db;

	public $item_per_page = 5;

	public $schema = array(
		"id_cotacao",
		"id_author",
		"id_empresa",
		"id_cliente",
		"id_vendedor",
		"id_forma_pagamento",
		"id_categoria",
		"projeto_code_sequencial",
		"projeto_code",
		"projeto_revisao",
		"projeto_cliente_nome",
		"projeto_contato",
		"projeto_email",
		"projeto_phone_01",
		"projeto_phone_02",
		"projeto_phone_03",
		"projeto_ramal",
		"projeto_status",
		"projeto_cadastro_data",
		"projeto_nome",
		"projeto_descricao",
		"create_time",
		"active"
	);

	public $projeto_status_array = array(
		'aprovado' => 'Aprovado',
		'suspenso' => 'Suspenso',
		'cancelado' => 'Cancelado',
		'finalizado' => 'Finalizado',
	);

	function __construct($db = false, $item_per_page = false){
		if(!$db){
			die();
		}
		$this->db = $db;

		if($item_per_page){
			$this->item_per_page = $item_per_page;
		}

	}

	public function insert($args = array()){

		$response = array(
			'result' => false
		);

		// Block if required fields isn't informed

		if(!isset($args['id_author'])){
			$response['error'] = 'O ID do autor é obrigatório.';
			return $response;
		}else{

			// checa se id do usuário existe
			$userSt = $this->db->select()->from('users')->whereMany(array('id' => $args['id_author'], 'active' => 'Y'), '=');
			$stmt = $userSt->execute();
			$user_data = $stmt->fetch();

			if(!$user_data){
				$response['error'] = 'Autor informado não existente.';
				return $response;
			}

		}

		if(!isset($args['id_cotacao'])){
			$response['error'] = 'O campo id_cotacao é obrigatório.';
			return $response;
		}else{

			// checa se projeto existe
			$cotacaoSt = $this->db->select()->from('projetos')->whereMany(array('id_cotacao' => $args['id_cotacao'], 'active' => 'Y'), '=');
			$stmt = $cotacaoSt->execute();
			$cotacao_data = $stmt->fetch();

			if($cotacao_data){
				$response['error'] = 'Já existe um projeto para essa cotação.';
				return $response;
			}

		}

		if(!isset($args['id_empresa'])){
			$response['error'] = 'O campo id_empresa é obrigatório.';
			return $response;
		}

		if(!isset($args['id_cliente'])){
			$response['error'] = 'O campo id_cliente é obrigatório.';
			return $response;
		}

		if(!isset($args['id_vendedor'])){
			$response['error'] = 'O campo id_vendedor é obrigatório.';
			return $response;
		}

		if(!isset($args['id_forma_pagamento'])){
			$response['error'] = 'O campo id_forma_pagamento é obrigatório.';
			return $response;
		}

		if(!isset($args['id_categoria'])){
			$response['error'] = 'O campo id_categoria é obrigatório.';
			return $response;
		}

		if(!isset($args['projeto_code'])){
			$response['error'] = 'O campo projeto_code é obrigatório.';
			return $response;
		}

		if(!isset($args['projeto_code_sequencial'])){
			$response['error'] = 'O campo projeto_code_sequencial é obrigatório.';
			return $response;
		}

		if(!isset($args['projeto_cadastro_data'])){
			$response['error'] = 'O campo projeto_cadastro_data é obrigatório.';
			return $response;
		}

		if(!isset($args['projeto_nome'])){
			$response['error'] = 'O campo projeto_nome é obrigatório.';
			return $response;
		}	 

		// Init insert
		$data = array_flip($this->schema);

		// External params
		foreach ($data as $field => $value) {
			
			if(isset($args[$field])){
				$val = $args[$field];
				$data[$field] = $val;
			}else{
				unset($data[$field]);
			}

		}

		// Fixed params
		$date = new \DateTime();
		$data['create_time'] = $date->format("Y-m-d\TH:i:s");

		$data['active'] = 'Y';

		$response['data'] = $data;

		$insertStatement = $this->db->insert(array_keys($data))->into('projetos')->values(array_values($data));

		$response['id'] = $insertStatement->execute();

		if(strlen($response['id']) > 0){
			$response['result'] = true;
		}

		return $response;

	}


	public function get($args = array()){

		$response = array();
		$is_search = false;
		
		// Count total
		$query_count = "SELECT COUNT(*) AS total FROM projetos p WHERE p.active = 'Y' ";

		// Filtro
		if(isset($args['projeto_num'])){
			if(strlen(trim($args['projeto_num'])) > 0){
				$query_count .= "AND p.projeto_code_sequencial LIKE '%".$args['projeto_num']."%' ";
				$is_search = true;
			}
		}

		if(isset($args['projeto_cliente'])){
			if(strlen(trim($args['projeto_cliente'])) > 0){
				$query_count .= "AND p.projeto_cliente_nome LIKE '%".$args['projeto_cliente']."%' ";
				$is_search = true;
			}
		}

		if(isset($args['projeto_nome'])){
			if(strlen(trim($args['projeto_nome'])) > 0){
				$query_count .= "AND p.projeto_nome LIKE '%".$args['projeto_nome']."%' ";
				$is_search = true;
			}
		}

		$count = $this->db->query($query_count);
		$total_data = $count->fetch();

		$config = array(
			'total' => $total_data['total'],
			'item_per_page' => $this->item_per_page,
			'total_pages' => ceil($total_data['total'] / $this->item_per_page),
			'current_page' => (isset($args['current_page']) ? $args['current_page'] : 1 ),
			'is_search' => $is_search
		);

		$response['config'] = $config;

		if($config['current_page'] <= $config['total_pages']){

			// Offset
			$offset = ($config['current_page'] == '1' ? 0 : ($config['current_page'] - 1) * $config['item_per_page'] );

			$query = "
				SELECT p.*, v.vendedor_nome
					FROM projetos p 
						INNER JOIN vendedores v 
						ON v.id = p.id_vendedor
					WHERE p.active = 'Y' 
			";

			// Filtro
			if(isset($args['projeto_num'])){
				if(strlen(trim($args['projeto_num'])) > 0){
					$query .= "AND p.projeto_code_sequencial LIKE '%".$args['projeto_num']."%' "; 
				}
			}

			if(isset($args['projeto_cliente'])){
				if(strlen(trim($args['projeto_cliente'])) > 0){
					$query .= "AND p.projeto_cliente_nome LIKE '%".$args['projeto_cliente']."%' "; 
				}
			}

			if(isset($args['projeto_nome'])){
				if(strlen(trim($args['projeto_nome'])) > 0){
					$query .= "AND p.projeto_nome LIKE '%".$args['projeto_nome']."%' "; 
				}
			}

			// Config
			$query .= "ORDER BY p.create_time DESC OFFSET ".$offset." ROWS FETCH NEXT ".$config['item_per_page']." ROWS ONLY";
			
			$select = $this->db->query($query);

			$response['results'] = $this->parser_fetch($select->fetchAll(\PDO::FETCH_ASSOC),'all');
			$response['config']['page_items_total'] = count($response['results']);

		}else{
			$response['results'] = [];
		}

		return $response;

	}

	public function parser_fetch($result, $fetch = 'one'){
		if($fetch == 'one'){
			$result = $this->apply_filter_projeto($result);
		}else{
			if($fetch == 'all'){
				foreach ($result as $key => $value) {
					$result[$key] = $this->apply_filter_projeto($value);
				}
			}
		}
		return $result;
	}

	public function apply_filter_projeto($projeto){

		foreach ($projeto as $key => $field) {
			$projeto[$key] = trim($field);
		}

		$cadastro_data = new \DateTime($projeto['projeto_cadastro_data']);
		$projeto['projeto_cadastro_data'] = $cadastro_data->getTimestamp();

		$projeto['projeto_status_text'] = $this->projeto_status_array[$projeto['projeto_status']];

		return $projeto;
	}

	public function delete($args = array()){

		$response = array(
			'result' => false
		);

		if(!isset($args['id'])){
			$response['error'] = 'ID do projeto não especificado';
			return $response;
		}

		$updateStatement = $this->db->update(array('active' => 'N'))->table('projetos')->where('id', '=', $args['id']);
		$affectedRows = $updateStatement->execute();

		if($affectedRows > 0){

			$response['result'] = true;
			$response['affectedRows'] = $affectedRows;
			return $response;

		}else{
			$response['error'] = 'Nenhum registro afetado.';
		}

		return $response;

	}

	public function update($args = array()){

		$response = array(
			'result' => false
		);

		if(!isset($args['context'])){
			$response = array(
				'result' => false,
				'error' => 'Contexto não definido'
			);
			return $response;
		}else{
			$context = $args['context'];
			unset($args['context']);
		}

		if(!isset($args['id'])){
			$response['error'] = 'ID não informado.';
			return $response;
		}else{
			$id = $args['id'];
			unset($args['id']);
		}

		$updateStatement = $this->db->update()->set($args)->table('projetos')->whereMany( array('id' => $id, 'id_empresa' => $context), '=');

		$affectedRows = $updateStatement->execute();

		if($affectedRows > 0){

			$response['result'] = true;
			$response['affectedRows'] = $affectedRows;
			return $response;

		}else{
			$response['error'] = 'Nenhum registro afetado.';
		}

		return $response;

	}


	public function get_by_id($id = false, $args = false){

		$response = array(
			'result' => false
		);

		if(!$id){
			$response['error'] = 'ID não informado.';
		}

		$selectStatement = $this->db->query("
			SELECT p.*, e.empresa_name, v.vendedor_nome, cat.cat_name, fg.forma_nome, cot.cotacao_observacoes_finais
				FROM projetos p 

					INNER JOIN empresas e 
					ON p.id_empresa = e.id

					INNER JOIN vendedores v 
					ON p.id_vendedor = v.id

					INNER JOIN categorias cat 
					ON p.id_categoria = cat.id

					INNER JOIN cotacoes cot 
					ON p.id_cotacao = cot.id

					LEFT OUTER JOIN formas_pagamento fg 
					ON p.id_forma_pagamento = fg.id

				WHERE p.id = '".$id."' AND p.active = 'Y'
		");

		$data = $selectStatement->fetch();

		if($data){
			$response['projeto'] = $this->parser_fetch($data);
			$response['result'] = true;
		}else{
			$response['error'] = 'Nenhum projeto encontrado para essa ID.';
		}

		return $response;

	}


	public function search($args = array()){

		$response = array('result' => false);

		if(!isset($args['context'])){
			$response['error'] = 'Contexto não definido.';
			return $response;
		}

		if(!isset($args['term'])){
			$response['error'] = 'Termo não definido.';
			return $response;
		}else{
			if(strlen(trim($args['term'])) == 0){
				$response['error'] = 'Termo não definido.';
				return $response;
			}
		}

		$term = trim($args['term']);
		
		$select = $this->db->query('SELECT * FROM projetos 
			WHERE active = \'Y\' 
			AND id_empresa = \''.$args['context'].'\' AND (
				projeto_apelido LIKE \'%'.$term.'%\' OR
				projeto_codigo = \''.$term.'\'
			)
			ORDER BY projeto_apelido');
			
		$response['results'] = $this->parser_fetch($select->fetchAll(\PDO::FETCH_ASSOC),'all');

		$response['result'] = true;

		return $response;

	}

	public function change_status($args){

		$response = array(
			'result' => false
		);

		if(!isset($args['id'])){
			$response['error'] = 'ID não definido';
			return $response;
		}

		if(!isset($args['status'])){
			$response['error'] = 'Status não definido';
			return $response;
		}else{

			if(!array_key_exists($args['status'], $this->projeto_status_array)){
				$response['error'] = 'Status incorreto';
				return $response;
			}

		}

		$updateStatement = $this->db->update()->set(array('projeto_status' => $args['status']))->table('projetos')->whereMany( array('id' => $args['id']), '=');

		$affectedRows = $updateStatement->execute();

		if($affectedRows > 0){

			$response['result'] = true;
			$response['affectedRows'] = $affectedRows;
			return $response;

		}else{
			$response['error'] = 'Nenhum registro afetado.';
		}

		return $response;


	}

}

?>