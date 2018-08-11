<?php

namespace Classes;

class Locais {

	private $db;

	public $item_per_page = 5;

	public $schema = array(
		"id_author",
		"id_empresa",
		"id_cliente",
		"local_apelido",
		"local_logradouro",
		"local_numero",
		"local_complemento",
		"local_bairro",
		"local_cidade",
		"local_cidade_ibge",
		"local_estado",
		"local_estado_ibge",
		"local_pais",
		"local_pais_ibge",
		"local_cep",
		"local_create_time",
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

		if(!isset($args['id_empresa'])){
			$response['error'] = 'O campo id_empresa é obrigatório.';
			return $response;
		}

		if(!isset($args['local_apelido'])){
			$response['error'] = 'O campo local_apelido é obrigatório.';
			return $response;
		}

		if(!isset($args['local_logradouro'])){
			$response['error'] = 'O campo local_logradouro é obrigatório.';
			return $response;
		}

		if(!isset($args['local_numero'])){
			$response['error'] = 'O campo local_numero é obrigatório.';
			return $response;
		}

		if(!isset($args['local_bairro'])){
			$response['error'] = 'O campo local_bairro é obrigatório.';
			return $response;
		}

		if(!isset($args['local_cidade'])){
			$response['error'] = 'O campo local_cidade é obrigatório.';
			return $response;
		}

		if(!isset($args['local_estado'])){
			$response['error'] = 'O campo local_estado é obrigatório.';
			return $response;
		}

		if(!isset($args['local_cep'])){
			$response['error'] = 'O campo local_cep é obrigatório.';
			return $response;
		}

		$args['id_cliente'] = (isset($args['id_cliente']) ? $args['id_cliente'] : 0);

		// Init insert
		$data = array_flip($this->schema);

		// External params
		foreach ($data as $field => $value) {
			
			if(isset($args[$field])){

				$val = $args[$field];

				// Tratamento de CEP
				if($field == 'local_cep'){
					$val = Utilities::unMask($val);
				}

				// Tratamento de id
				if($field == 'id_author'){
					$val = intval($val);
				}

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

		$insertStatement = $this->db->insert(array_keys($data))->into('locais')->values(array_values($data));

		$response['id'] = $insertStatement->execute();

		if(strlen($response['id']) > 0){
			$response['result'] = true;
		}

		return $response;

	}


	public function get($args = array()){

		$response = array();

		if(!isset($args['context'])){
			$response = array(
				'result' => false,
				'error' => 'Contexto não definido'
			);
			return $response;
		}

		$args['getall'] = (isset($args['getall']) ? $args['getall'] : false);

		// Count total
		$selectStatement = $this->db->select(array('COUNT(*) AS total'))->from('locais')->whereMany(array('active' => 'Y', 'id_empresa' => $args['context']),'=');
		$stmt = $selectStatement->execute();
		$total_data = $stmt->fetch();

		if($args['getall'] == '1'){

			$config = array(
				'total' => $total_data['total'],
			);

			$response['config'] = $config;

			$select = $this->db->query('SELECT * FROM locais WHERE id_empresa = \''.$args['context'].'\' AND active = \'Y\' ORDER BY create_time');
			$response['results'] = $this->parser_fecth($select->fetchAll(\PDO::FETCH_ASSOC),'all');

		}else{

			$config = array(
				'total' => $total_data['total'],
				'item_per_page' => $this->item_per_page,
				'total_pages' => ceil($total_data['total'] / $this->item_per_page),
				'current_page' => (isset($args['current_page']) ? $args['current_page'] : 1 )
			);

			$response['config'] = $config;

			if($config['current_page'] <= $config['total_pages']){

				$offset = ($config['current_page'] == '1' ? 0 : ($config['current_page'] - 1) * $config['item_per_page'] );
				$select = $this->db->query('SELECT * FROM locais WHERE id_empresa = \''.$args['context'].'\' AND active = \'Y\' ORDER BY create_time OFFSET '.$offset.' ROWS FETCH NEXT '.$config['item_per_page'].' ROWS ONLY');
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
			$result = $this->apply_filter_local($result);
		}else{
			if($fetch == 'all'){
				foreach ($result as $key => $value) {
					$result[$key] = $this->apply_filter_local($value);
				}
			}
		}
		return $result;
	}

	public function apply_filter_local($local){

		foreach ($local as $key => $field) {
			$local[$key] = trim($field);
		}

		
		return $local;
	}

	public function delete($args = array()){

		$response = array(
			'result' => false
		);

		if(!isset($args['id'])){
			$response['error'] = 'ID do local não especificado';
			return $response;
		}

		$updateStatement = $this->db->update(array('active' => 'N'))->table('locais')->where('id', '=', $args['id']);
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

		$updateStatement = $this->db->update()->set($args)->table('locais')->whereMany( array('id' => $id, 'id_empresa' => $context), '=');

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

		if(!isset($args['context'])){
			$response = array(
				'result' => false,
				'error' => 'Contexto não definido'
			);
			return $response;
		}

		if(!$id){
			$response['error'] = 'ID não informado.';
		}

		$selectStatement = $this->db->select()->from('locais')->whereMany(array('id' => $id, 'active' => 'Y', 'id_empresa' => $args['context'] ), '=');

		$stmt = $selectStatement->execute();
		$data = $stmt->fetch();

		if($data){
			$response['local'] = $this->parser_fecth($data);
			$response['result'] = true;
		}else{
			$response['error'] = 'Nenhum local encontrada para essa ID.';
		}

		return $response;

	}

}

?>