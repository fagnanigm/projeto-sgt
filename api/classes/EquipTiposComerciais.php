<?php

namespace Classes;

class EquipTiposComerciais {

	private $db;

	public $item_per_page = 5;

	public $schema = array(
		"id_author",
		"tipo_nome",
		"tipo_descricao",
		"tipo_price",
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

		if(!isset($args['tipo_nome'])){
			$response['error'] = 'O campo tipo_nome é obrigatório.';
			return $response;
		} 

		if(!isset($args['tipo_descricao'])){
			$response['error'] = 'O campo tipo_descricao é obrigatório.';
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

		$insertStatement = $this->db->insert(array_keys($data))->into('equip_tipos_comerciais')->values(array_values($data));

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
		$query_count = "SELECT COUNT(*) AS total FROM equip_tipos_comerciais WHERE active = 'Y'";

		// Filtro
		if(isset($args['tipo_term'])){
			$query_count .= " AND tipo_nome LIKE '%".$args['tipo_term']."%'";
			$is_search = true;
		}

		$count = $this->db->query($query_count);
		$total_data = $count->fetch();

		if($args['getall'] == '1'){

			$config = array(
				'total' => $total_data['total'],
			);

			$response['config'] = $config;

			$select = $this->db->query('SELECT * FROM equip_tipos_comerciais WHERE active = \'Y\' ORDER BY tipo_nome');
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

				$query = "SELECT * FROM equip_tipos_comerciais WHERE active = 'Y' ";

				// Filtro
				if(isset($args['tipo_term'])){
					$query .= " AND tipo_nome LIKE '%".$args['tipo_term']."%' ";
				}

				// Config
				$query .= "ORDER BY tipo_nome OFFSET ".$offset." ROWS FETCH NEXT ".$config['item_per_page']." ROWS ONLY";

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
			$result = $this->apply_filter_tipo($result);
		}else{
			if($fetch == 'all'){
				foreach ($result as $key => $value) {
					$result[$key] = $this->apply_filter_tipo($value);
				}
			}
		}
		return $result;
	}

	public function apply_filter_tipo($tipo){

		foreach ($tipo as $key => $field) {
			$tipo[$key] = trim($field);
		}
		
		$create_time = new \DateTime($tipo['create_time']);
		$tipo['create_timestamp'] = $create_time->getTimestamp();
		
		return $tipo;
	}

	public function delete($args = array()){

		$response = array(
			'result' => false
		);

		if(!isset($args['id'])){
			$response['error'] = 'ID do tipo não especificado';
			return $response;
		}

		$updateStatement = $this->db->update(array('active' => 'N'))->table('equip_tipos_comerciais')->where('id', '=', $args['id']);
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

		$updateStatement = $this->db->update()->set($data)->table('equip_tipos_comerciais')->whereMany( array('id' => $id), '=');

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

		$selectStatement = $this->db->select()->from('equip_tipos_comerciais')->whereMany(array('id' => $id, 'active' => 'Y' ), '=');

		$stmt = $selectStatement->execute();
		$data = $stmt->fetch();

		if($data){
			$response['data'] = $this->parser_fecth($data);
			$response['result'] = true;
		}else{
			$response['error'] = 'Nenhum tipo encontrado para essa ID.';
		}

		return $response;

	}

}

?>