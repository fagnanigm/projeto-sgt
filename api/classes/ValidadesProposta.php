<?php

namespace Classes;

class ValidadesProposta {

	private $db;

	public $item_per_page = 5;

	public $schema = array(
		"validade_nome",
		"create_time",
		"active"
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

		if(!isset($args['validade_nome'])){
			$response['error'] = 'O campo validade_nome é obrigatório.';
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

		$insertStatement = $this->db->insert(array_keys($data))->into('validades_proposta')->values(array_values($data));

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
		$query_count = "SELECT COUNT(*) AS total FROM validades_proposta WHERE active = 'Y'";

		// Filtro
		if(isset($args['validade_nome'])){
			$query_count .= " AND validade_nome LIKE '%".$args['validade_nome']."%'";
			$is_search = true;
		}

		$count = $this->db->query($query_count);
		$total_data = $count->fetch();

		if($args['getall'] == '1'){

			$config = array(
				'total' => $total_data['total'],
			);

			$response['config'] = $config;

			$select = $this->db->query('SELECT * FROM validades_proposta WHERE active = \'Y\' ORDER BY validade_nome');
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

				$query = "SELECT * FROM validades_proposta WHERE active = 'Y' ";

				// Filtro
				if(isset($args['validade_nome'])){
					$query .= " AND validade_nome LIKE '%".$args['validade_nome']."%' ";
				}

				// Config
				$query .= "ORDER BY validade_nome OFFSET ".$offset." ROWS FETCH NEXT ".$config['item_per_page']." ROWS ONLY";

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

	public function delete($args = array()){

		$response = array(
			'result' => false
		);

		if(!isset($args['id'])){
			$response['error'] = 'ID da validade da proposta não especificado';
			return $response;
		}

		$updateStatement = $this->db->update(array('active' => 'N'))->table('validades_proposta')->where('id', '=', $args['id']);
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

		$updateStatement = $this->db->update()->set($data)->table('validades_proposta')->whereMany( array('id' => $id), '=');

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

		$selectStatement = $this->db->select()->from('validades_proposta')->whereMany(array('id' => $id, 'active' => 'Y' ), '=');

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

}

?>