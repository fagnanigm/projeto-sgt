<?php

namespace Classes;

class IncluirServico {

	private $db;

	public $item_per_page = 5;

	 public $schema = array(
		"cCodigo",
		"cCodIntServ",
		"cDescricao",
		"cIdTrib",
		"cCodServMun",
		"cCodLC116",
		"nIdNBS",
		"nPrecoUnit",
		"cDescrCompleta",
		"nAliqISS",
		"cRetISS",
		"nAliqPIS",
		"cRetPIS",
		"nAliqCOFINS",
		"cRetCOFINS",
		"nAliqCSLL",
		"cRetCSLL",
		"nAliqIR",
		"cRetIR",
		"nAliqINSS",
		"cRetINSS",
		"nRedBaseINSS"
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

		if(!isset($args['cCodigo'])){
			$response['error'] = 'O Código do Serviço é obrigatório.';
			return $response;
		}else{

			// checa se id do usuário existe
			$userSt = $this->db->select()->from('users')->whereMany(array('id' => $args['cCodigo'], 'active' => 'Y'), '=');
			$stmt = $userSt->execute();
			$user_data = $stmt->fetch();

			if(!$user_data){
				$response['error'] = 'Código do Serviço informado não existente.';
				return $response;
			}

		}

		if(!isset($args['cCodIntServ'])){
			$response['error'] = 'O campo Código de Integração do Serviço é obrigatório.';
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



	public function get($args = array()){

		$response = array();
		$is_search = false;

		$args['getall'] = (isset($args['getall']) ? $args['getall'] : false);

		// Count total
		$query_count = "SELECT COUNT(*) AS total FROM cCodigo WHERE active = 'Y'";

		// Filtro
		if(isset($args['forma_term'])){
			$query_count .= " AND forma_nome LIKE '%".$args['forma_term']."%'";
			$is_search = true;
		}

		$count = $this->db->query($query_count);
		$total_data = $count->fetch();

		if($args['getall'] == '1'){

			$config = array(
				'total' => $total_data['total'],
			);

			$response['config'] = $config;

			$select = $this->db->query('SELECT * FROM cCodigo WHERE active = \'Y\' ORDER BY forma_nome');
			$response['results'] = $this->parser_fecth($select->fetchAll(\PDO::FETCH_ASSOC),'all');

		}else{

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

				$query = "SELECT * FROM cCodigo WHERE active = 'Y' ";

				// Filtro
				if(isset($args['forma_term'])){
					$query .= " AND forma_nome LIKE '%".$args['forma_term']."%' ";
				}

				// Config
				$query .= "ORDER BY forma_nome OFFSET ".$offset." ROWS FETCH NEXT ".$config['item_per_page']." ROWS ONLY";

				$select = $this->db->query($query);
				$response['results'] = $this->parser_fecth($select->fetchAll(\PDO::FETCH_ASSOC),'all');
				$response['config']['page_items_total'] = count($response['results']);

			}else{
				$response['results'] = [];
			}

		}

		return $response;

	}

	public function parser_fecth($result, $fetch = 'one'){
		if($fetch == 'one'){
			$result = $this->apply_filter_forma($result);
		}else{
			if($fetch == 'all'){
				foreach ($result as $key => $value) {
					$result[$key] = $this->apply_filter_forma($value);
				}
			}
		}
		return $result;
	}

	public function apply_filter_forma($forma){

		foreach ($forma as $key => $field) {
			$forma[$key] = trim($field);
		}
		
		$create_time = new \DateTime($forma['create_time']);
		$forma['create_timestamp'] = $create_time->getTimestamp();
		
		return $forma;
	}

	public function delete($args = array()){

		$response = array(
			'result' => false
		);

		if(!isset($args['id'])){
			$response['error'] = 'ID da forma de pagamento não especificado';
			return $response;
		}

		$updateStatement = $this->db->update(array('active' => 'N'))->table('cCodigo')->where('id', '=', $args['id']);
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

		if(!isset($args['id'])){
			$response['error'] = 'ID não informado.';
			return $response;
		}else{
			$id = $args['id'];
			unset($args['id']);
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

		$updateStatement = $this->db->update()->set($data)->table('cCodigo')->whereMany( array('id' => $id), '=');

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

		$selectStatement = $this->db->select()->from('formas_pagamento')->whereMany(array('id' => $id, 'active' => 'Y' ), '=');

		$stmt = $selectStatement->execute();
		$data = $stmt->fetch();

		if($data){
			$response['data'] = $this->parser_fecth($data);
			$response['result'] = true;
		}else{
			$response['error'] = 'Nenhuma forma de pagamento encontrada para essa ID.';
		}

		return $response;

	}

}

?>