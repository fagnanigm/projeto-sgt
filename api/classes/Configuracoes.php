<?php

namespace Classes;

class Configuracoes {

	private $db;

	public $schema = array(
		"meta_key",
		"meta_value"
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

		if(!isset($args['meta_key'])){
			$response['error'] = 'A meta_key é obrigatória.';
			return $response;
		}

		if($this->check_meta_key($args['meta_key'])){
			$response['error'] = 'Meta_key já utilizada.';
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

		$response['data'] = $data;

		$insertStatement = $this->db->insert(array_keys($data))->into('configuracoes')->values(array_values($data));

		$response['id'] = $insertStatement->execute();

		if(strlen($response['id']) > 0){
			$response['result'] = true;
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

		$updateStatement = $this->db->update()->set($args)->table('configuracoes')->where('id', '=', $id);

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


	public function get_by_args($args = false){

		$response = array(
			'result' => false
		);		

		if(!$args){
			$response['error'] = 'ID não informado.';
		}

		$selectStatement = $this->db->select()->from('configuracoes')->where('id', '=', abs($args))->orWhere('meta_key', '=', $args);
		$stmt = $selectStatement->execute();
		$data = $stmt->fetch();

		if($data){
			$response['configuracao'] = $data;
			$response['result'] = true;
		}else{
			$response['error'] = 'Nenhuma configuração encontrada para essa ID.';
		}

		return $response;

	}

	public function check_meta_key($meta_key){

		$selectStatement = $this->db->select()->from('configuracoes')->where('meta_key', '=', $meta_key);
		$stmt = $selectStatement->execute();
		$data = $stmt->fetch();

		return (!$data ? false : true);

	}

}

?>