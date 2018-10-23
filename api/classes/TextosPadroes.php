<?php

namespace Classes;

class TextosPadroes {

	private $db;

	public $schema = array(
        "texto_field",
        "texto_descricao",
        "create_time",
        "active"
	);

	function __construct($db = false){
		if(!$db){
			die();
		}
		$this->db = $db;
	}

	public function set($args = array()){

		$response = array(
			'result' => false
		);

		if(!isset($args['texto_field'])){
			$response['error'] = 'O campo texto_field é obrigatório.';
			return $response;
		}else{

			$texto_padrao_st = $this->db->select()->from('textos_padroes')->whereMany(array('texto_field' => $args['texto_field']), '=');
			$stmt = $texto_padrao_st->execute();
			$texto_padrao_data = $stmt->fetch();
			
		}

		if(!isset($args['texto_descricao'])){
			$response['error'] = 'O campo texto_descricao é obrigatório.';
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

		if(!$texto_padrao_data){
			$insertStatement = $this->db->insert(array_keys($data))->into('textos_padroes')->values(array_values($data));
			$response['id'] = $insertStatement->execute();
		}else{

			$updateStatement = $this->db->update()->set($data)->table('textos_padroes')->where('id', '=', $texto_padrao_data['id']);
			$affectedRows = $updateStatement->execute();

			if($affectedRows > 0){

				$response['result'] = true;
				return $response;

			}else{
				$response['error'] = 'Nenhum registro afetado.';
			}

		}	

		if(strlen($response['id']) > 0){
			$response['result'] = true;
		}

		return $response;

	}


	public function get($args = array()){

		$response = array();
		$is_search = false;

		$query = 'SELECT * FROM textos_padroes WHERE active = \'Y\' ';

		if(isset($args['keys'])){

			$keys = explode(",", $args['keys']);

			$query .= ' AND (';

			foreach ($keys as $key => $value) {
				$query .= " texto_field = '".trim($value)."' OR ";
			}


			$query = substr($query, 0, -3). ' ) ';

		}

		$query .= ' ORDER BY id; ';


		$select = $this->db->query($query);
		$response['results'] = $this->parser_fecth($select->fetchAll(\PDO::FETCH_ASSOC),'all');

		return $response;

	}

	public function parser_fecth($result, $fetch = 'one'){

		$result_parsed = array();

		if($fetch == 'one'){
			$result = $this->apply_filter($result);
		}else{
			if($fetch == 'all'){
				foreach ($result as $key => $value) {
					$result_parsed[$value['texto_field']] = $this->apply_filter($value);
				}
			}
		}
		return $result_parsed;
	}

	public function apply_filter($campos){

		foreach ($campos as $key => $field) {
			$campos[$key] = trim($field);
		}
		
		$create_time = new \DateTime($campos['create_time']);
		$campos['create_timestamp'] = $create_time->getTimestamp();
		
		return $campos;
	}

	public function get_by_term($term = false){

		$response = array(
			'result' => false
		);

		if(!$term){
			$response['error'] = 'ID não informado.';
			return $response;
		}

		$selectStatement = $this->db->select()->from('textos_padroes')->whereMany(array('texto_field' => $term, 'active' => 'Y' ), '=');

		$stmt = $selectStatement->execute();
		$data = $stmt->fetch();

		if($data){
			$response['data'] = $this->parser_fecth($data);
			$response['result'] = true;
		}else{
			$response['error'] = 'Nenhuma validade da proposta encontrada para esse termo.';
		}

		return $response;

	}

}

?>