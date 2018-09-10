<?php

namespace Classes;

class Vendedores {

	private $db;

	public $schema = array( 
		"id_author",
		"vendedor_nome",
		"vendedor_telefone",
		"vendedor_email",
		"create_time",
		"active"
	);

	public $item_per_page = 5;

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
			$response['error'] = 'O campo id_author é obrigatório.';
			return $response;
		}

		if(!isset($args['vendedor_nome'])){
			$response['error'] = 'O campo vendedor_nome é obrigatório.';
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

		$insertStatement = $this->db->insert(array_keys($data))->into('vendedores')->values(array_values($data));
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
		$query_count = "SELECT COUNT(*) AS total FROM vendedores WHERE active = 'Y'";

		// Filtro
		if(isset($args['vendedor_term'])){
			$query_count .= " AND ( ";
				$query_count .= "vendedor_nome LIKE '%".$args['vendedor_term']."%' OR ";
				$query_count .= "vendedor_email LIKE '%".$args['vendedor_term']."%' ";
			$query_count .= " )";
			$is_search = true;
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

			$query = "SELECT * FROM vendedores WHERE active = 'Y' ";

			// Filtro
			if(isset($args['vendedor_term'])){
				$query .= " AND ( ";
					$query .= "vendedor_nome LIKE '%".$args['vendedor_term']."%' OR ";
					$query .= "vendedor_email LIKE '%".$args['vendedor_term']."%' ";
				$query .= " ) ";
			}

			// Config
			$query .= "ORDER BY create_time OFFSET ".$offset." ROWS FETCH NEXT ".$config['item_per_page']." ROWS ONLY";

			$select = $this->db->query($query);
			$response['results'] = $this->parser_fecth($select->fetchAll(\PDO::FETCH_ASSOC),'all');
			$response['config']['page_items_total'] = count($response['results']);

		}else{
			$response['results'] = [];
		}

		return $response;

	}

	public function parser_fecth($result, $fetch = 'one'){
		if($fetch == 'one'){
			$result = $this->apply_filter_vendedor($result);
		}else{
			if($fetch == 'all'){
				foreach ($result as $key => $value) {
					$result[$key] = $this->apply_filter_vendedor($value);
				}
			}
		}
		return $result;
	}

	public function apply_filter_vendedor($cat){
		
		foreach ($cat as $key => $field) {
			$cat[$key] = trim($field);
		}
		
		$create_time = new \DateTime($cat['create_time']);
		$cat['create_timestamp'] = $create_time->getTimestamp();

		return $cat;
	}

	public function delete($args = array()){

		$response = array(
			'result' => false
		);

		if(!isset($args['id'])){
			$response['error'] = 'ID do vendedor não especificado.';
			return $response;
		}

		$updateStatement = $this->db->update(array('active' => 'N'))->table('vendedores')->where('id', '=', $args['id']);
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

		unset($args['create_timestamp']);

		$updateStatement = $this->db->update()->set($args)->table('vendedores')->whereMany( array('id' => $id, 'active' => 'Y'), '=');

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

		$selectStatement = $this->db->select()->from('vendedores')->whereMany(array('id' => $id, 'active' => 'Y'), '=' );

		$stmt = $selectStatement->execute();
		$data = $stmt->fetch();

		if($data){
			$response['vendedor'] = $this->parser_fecth($data);
			$response['result'] = true;
		}else{
			$response['error'] = 'Nenhuma categoria encontrada.';
		}

		return $response;

	}

}

?>