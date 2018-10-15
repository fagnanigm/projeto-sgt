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

			if(is_array($args['taxa_arquivos'])){

				foreach ($args['taxa_arquivos'] as $key => $anexo) {

					$data = array(
						'id_taxa' => $response['id'],
						'id_arquivo' => $anexo['id'],
						'create_time' => $date->format("Y-m-d\TH:i:s")
					);
					
					$insertStatement = $this->db->insert(array_keys($data))->into('taxas_licencas_arquivos')->values(array_values($data));
					$insertStatement->execute();

				}

			}

		}

		return $response;

	}


	public function get($args = array()){

		$response = array();

		if(!isset($args['id_as'])){
			$response['error'] = 'O campo id_as é obrigatório.';
			return $response;
		}

		$query = "
			SELECT tl.* , tp.tipo_nome as taxa_tipo_text
			FROM taxas_licencas tl 
				INNER JOIN taxas_licencas_tipos tp 
				ON tp.id = tl.id_tipo
			WHERE tl.id_as = '".$args['id_as']."'
		;";
					
		$select = $this->db->query($query);
		$response['results'] = $this->parser_fetch($select->fetchAll(\PDO::FETCH_ASSOC),'all');

		return $response;
		
	}

	public function parser_fetch($result, $fetch = 'one'){
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
		
		// Create time
		$create_time = new \DateTime($campos['create_time']);
		$campos['create_timestamp'] = $create_time->getTimestamp();

		// DATA : taxa_previsao_pagamento 
		$taxa_previsao_pagamento = new \DateTime($campos['taxa_previsao_pagamento']);
		$campos['taxa_previsao_pagamento'] = $taxa_previsao_pagamento->getTimestamp();

		// Taxas de arquivos

		$select = $this->db->query("
			SELECT a.* FROM taxas_licencas_arquivos ca
				LEFT JOIN arquivos a 
				ON ca.id_arquivo = a.id
			WHERE ca.id_taxa = '".$campos['id']."';
		");

		$taxa_arquivos = $select->fetchAll(\PDO::FETCH_ASSOC);

		if(is_array($taxa_arquivos)){
			foreach ($taxa_arquivos as $key => $value) {	
				$create_time = new \DateTime($value['create_time']);
				$taxa_arquivos[$key]['create_timestamp'] = $create_time->getTimestamp();
			}
		}else{
			$taxa_arquivos = array();
		}

		$campos['taxa_arquivos'] = $taxa_arquivos;

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