<?php

namespace Classes;

class TaxasLicencas {

	private $db;

	public $item_per_page = 5;

	public $schema = array(
		"id_as",
		"id_categoria",
		"id_tipo",
		"taxa_code",
		"taxa_num_sequencial",
		"taxa_fornecedor",
		"taxa_aet",
		"taxa_valor",
		"taxa_previsao_pagamento",
		"taxa_numero_nota_fiscal",
		"taxa_forma_pagamento",
		"taxa_status",
		"create_time",
		"active"
	);

	public $taxa_status = array(
		'pago' => 'Pago',
		'pendente' => 'Pendente'
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

		if(!isset($args['id_as'])){
			$response['error'] = 'O campo id_as é obrigatório.';
			return $response;
		} 

		if(!isset($args['id_categoria'])){
			$response['error'] = 'O campo id_categoria é obrigatório.';
			return $response;
		} 

		if(!isset($args['id_tipo'])){
			$response['error'] = 'O campo id_tipo é obrigatório.';
			return $response;
		} 

		if(!isset($args['taxa_code'])){
			$response['error'] = 'O campo taxa_code é obrigatório.';
			return $response;
		}

		if(!isset($args['taxa_status'])){
			$response['error'] = 'O campo taxa_status é obrigatório.';
			return $response;
		}else{

			if(!array_key_exists($args['taxa_status'], $this->taxa_status)){
				$response['error'] = 'O campo taxa_status está inválido.';
				return $response;
			}

		}

		// Init insert
		$data = array_flip($this->schema);

		// External params
		foreach ($data as $field => $value) {
			
			if(isset($args[$field])){
				$val = $args[$field];

				// Tratamento de data
				if(	
					$field == 'taxa_previsao_pagamento'
				){
					$date = new \DateTime();
					$date->setTimestamp($val);
					$val = $date->format("Y-m-d\TH:i:s");
				}

				$data[$field] = $val;
			}else{
				unset($data[$field]);
			}

		}

		$data['taxa_num_sequencial'] = $this->get_sequencial_from_code($data['taxa_code']);

		if(!$data['taxa_num_sequencial']){
			$response['error'] = 'Formato do código incorreto';
			return $response;
		}

		// Fixed params
		$date = new \DateTime();
		$data['create_time'] = $date->format("Y-m-d\TH:i:s");

		$data['active'] = 'Y';

		$response['data'] = $data;

		$insertStatement = $this->db->insert(array_keys($data))->into('taxas_licencas')->values(array_values($data));

		$response['id'] = $insertStatement->execute();

		if(strlen($response['id']) > 0){
			$response['result'] = true;
		}

		return $response;

	}


	public function get($args = array()){

		$response = array();
		$is_search = false;

		$args['getall'] = (isset($args['getall']) ? $args['getall'] : false);

		// Count total
		$query_count = "SELECT COUNT(*) AS total FROM taxas_licencas WHERE active = 'Y'";

		// Filtro
		if(isset($args['tipo_nome'])){
			$query_count .= " AND tipo_nome LIKE '%".$args['tipo_nome']."%'";
			$is_search = true;
		}

		$count = $this->db->query($query_count);
		$total_data = $count->fetch();

		if($args['getall'] == '1'){

			$config = array(
				'total' => $total_data['total'],
			);

			$response['config'] = $config;

			$select = $this->db->query('SELECT * FROM taxas_licencas WHERE active = \'Y\' ORDER BY tipo_nome');
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

				$query = "SELECT * FROM taxas_licencas WHERE active = 'Y' ";

				// Filtro
				if(isset($args['tipo_nome'])){
					$query .= " AND tipo_nome LIKE '%".$args['tipo_nome']."%' ";
				}

				// Config
				$query .= "ORDER BY id OFFSET ".$offset." ROWS FETCH NEXT ".$config['item_per_page']." ROWS ONLY";

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
			$result = $this->apply_filter($result);
		}else{
			if($fetch == 'all'){
				foreach ($result as $key => $value) {
					$result[$key] = $this->apply_filter($value);
				}
			}
		}
		return $result;
	}

	public function apply_filter($campos){

		foreach ($campos as $key => $field) {
			$campos[$key] = trim($field);
		}
		
		$create_time = new \DateTime($campos['create_time']);
		$campos['create_timestamp'] = $create_time->getTimestamp();
		
		return $campos;
	}

	public function get_by_id($id = false, $args = false){

		$response = array(
			'result' => false
		);

		if(!$id){
			$response['error'] = 'ID não informado.';
		}

		$selectStatement = $this->db->select()->from('taxas_licencas')->whereMany(array('id' => $id, 'active' => 'Y' ), '=');

		$stmt = $selectStatement->execute();
		$data = $stmt->fetch();

		if($data){
			$response['data'] = $this->parser_fecth($data);
			$response['result'] = true;
		}else{
			$response['error'] = 'Nenhuma validade da proposta encontrada para essa ID.';
		}

		return $response;

	}

	public function get_sequencial_from_code($code){

		$code = explode("-", trim($code));

		if(isset($code[1])){
			return str_pad(trim($code[1]), 6, "0", STR_PAD_LEFT);
		}else{
			return false;
		}

	}

}

?>