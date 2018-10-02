<?php

namespace Classes;

class AsCargas {

	private $db;

	public $schema = array(
		"id_as",
		"objeto_item",
		"dado_numero",
		"dado_nota_fiscal",
		"dado_quantidade",
		"dado_mercadoria",
		"dado_especie",
		"dado_comprimento",
		"dado_largura",
		"dado_altura",
		"dado_peso",
		"dado_valor",
		"dado_frete_peso",
		"create_time",
		"active",
		"dado_nota_fiscal_chave"
	);

	function __construct($db = false){
		if(!$db){
			die();
		}
		$this->db = $db;
	}

	public function insert($args = array()){

		$response = array(
			'result' => false
		);

		// Block if required fields isn't informed
		if(!isset($args['id_as'])){
			$response['error'] = 'O campo id_as é obrigatório.';
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

		$insertStatement = $this->db->insert(array_keys($data))->into('autorizacao_servico_dados_carga')->values(array_values($data));

		$response['id'] = $insertStatement->execute();

		if(strlen($response['id']) > 0){
			$response['result'] = true;
		}

		return $response;

	}


	public function get($args = array()){

		$response = array();

		if(!isset($args['id_as'])){
			$response['error'] = 'O campo id_as é obrigatório.';
			return $response;
		}

		$query = "SELECT * FROM autorizacao_servico_dados_carga WHERE id_as = '".$args['id_as']."';";
					
		$select = $this->db->query($query);
		$response['results'] = $this->parser_fetch($select->fetchAll(\PDO::FETCH_ASSOC),'all');

		return $response;

	}

	public function parser_fetch($result, $fetch = 'one'){
		if($fetch == 'one'){
			$result = $this->apply_filter_objeto($result);
		}else{
			if($fetch == 'all'){
				foreach ($result as $key => $value) {
					$result[$key] = $this->apply_filter_objeto($value);
				}
			}
		}
		return $result;
	}

	public function apply_filter_objeto($obj){

		foreach ($obj as $key => $field) {
			$obj[$key] = trim($field);
		}

		$create_time = new \DateTime($obj['create_time']);
		$obj['create_timestamp'] = $create_time->getTimestamp();

		return $obj;
	}

}

?>