<?php

namespace Classes;

class Empresas {

	private $db;

	public $item_per_page = 5;

	public $schema = array(
		"id_author",
		"empresa_name",
		"empresa_prefixo",
		"empresa_nome_fantasia",
		"empresa_razao_social",
		"empresa_name",
		"empresa_responsavel",
		"empresa_email",
		"empresa_phone_ddd",
		"empresa_phone",
		"empresa_cnpj",
		"empresa_ie",
		"empresa_im",
		"empresa_cep",
		"empresa_logradouro",
		"empresa_numero",
		"empresa_complemento",
		"empresa_bairro",
		"empresa_cidade",
		"empresa_estado",
		"empresa_app_key",
		"empresa_app_secret",
		"active",
		"create_time"
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

		if(!isset($args['empresa_name'])){
			$response['error'] = 'O nome da empresa é obrigatório.';
			return $response;
		}

		if(!isset($args['empresa_cnpj'])){
			$response['error'] = 'O CNPJ é obrigatório.';
			return $response;
		}

		if(!isset($args['empresa_prefixo'])){
			$response['error'] = 'O campo empresa_prefixo é obrigatório.';
			return $response;
		}

		// Init insert

		$data = array_flip($this->schema);

		// External params
		foreach ($data as $field => $value) {
			
			if(isset($args[$field])){

				$val = $args[$field];

				// Tratamento de CNPJ
				if($field == 'empresa_cnpj'){
					$val = Utilities::unMask($val);
				}

				// Tratamento de IE
				if($field == 'empresa_ie'){
					$val = Utilities::unMask($val);
				}

				// Tratamento de CEP
				if($field == 'empresa_cep'){
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

		$insertStatement = $this->db->insert(array_keys($data))->into('empresas')->values(array_values($data));

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
		$query_count = "SELECT COUNT(*) AS total FROM empresas WHERE active = 'Y'";

		// Filtro
		if(isset($args['empresa_term'])){
			$query_count .= " AND ( ";
				$query_count .= "empresa_name LIKE '%".$args['empresa_term']."%' OR ";
				$query_count .= "empresa_nome_fantasia LIKE '%".$args['empresa_term']."%' OR ";
				$query_count .= "empresa_razao_social LIKE '%".$args['empresa_term']."%' ";
			$query_count .= " ) ";
			$is_search = true;
		}

		$count = $this->db->query($query_count);
		$total_data = $count->fetch();

		if($args['getall'] == '1'){

			$config = array(
				'total' => $total_data['total'],
			);

			$response['config'] = $config;

			$select = $this->db->query('SELECT * FROM empresas WHERE active = \'Y\' ORDER BY create_time');
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

				$query = "SELECT * FROM empresas WHERE active = 'Y' ";

				// Filtro
				if(isset($args['empresa_term'])){
					$query .= " AND ( ";
						$query .= "empresa_name LIKE '%".$args['empresa_term']."%' OR ";
						$query .= "empresa_nome_fantasia LIKE '%".$args['empresa_term']."%' OR ";
						$query .= "empresa_razao_social LIKE '%".$args['empresa_term']."%' ";
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

		}

		return $response;

	}

	public function parser_fecth($result, $fetch = 'one'){
		if($fetch == 'one'){
			$result = $this->apply_filter_empresa($result);
		}else{
			if($fetch == 'all'){
				foreach ($result as $key => $value) {
					$result[$key] = $this->apply_filter_empresa($value);
				}
			}
		}
		return $result;
	}

	public function apply_filter_empresa($empresa){

		foreach ($empresa as $key => $field) {
			$empresa[$key] = trim($field);
		}

		if(strlen($empresa['empresa_cnpj']) > 0){
			$empresa['empresa_cnpj'] = Utilities::mask($empresa['empresa_cnpj'],'##.###.###/####-##');
		}

		if(strlen($empresa['empresa_cep']) > 0){
			$empresa['empresa_cep'] = Utilities::mask($empresa['empresa_cep'],'#####-###');
		}

		$create_time = new \DateTime($empresa['create_time']);
		$empresa['create_timestamp'] = $create_time->getTimestamp();

		return $empresa;
	}

	public function delete($args = array()){

		$response = array(
			'result' => false
		);

		if(!isset($args['id'])){
			$response['error'] = 'ID da empresa não especificado';
			return $response;
		}

		$updateStatement = $this->db->update(array('active' => 'N'))->table('empresas')->where('id', '=', $args['id']);
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

		// Tratamento de CNPJ
		if(isset($args['empresa_cnpj'])){
			$args['empresa_cnpj'] = Utilities::unMask($args['empresa_cnpj']);
		}

		// Tratamento de IE
		if(isset($args['empresa_ie'])){
			$args['empresa_ie'] = Utilities::unMask($args['empresa_ie']);
		}

		// Tratamento de CEP
		if(isset($args['empresa_cep'])){
			$args['empresa_cep'] = Utilities::unMask($args['empresa_cep']);
		}

		// Timestamp
		if(isset($args['create_timestamp'])){
			unset($args['create_timestamp']);
		}

		$updateStatement = $this->db->update()->set($args)->table('empresas')->where('id', '=', $id);

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


	public function get_by_id($id = false){

		$response = array(
			'result' => false
		);		

		if(!$id){
			$response['error'] = 'ID não informado.';
		}

		$selectStatement = $this->db->select()->from('empresas')->whereMany(array('id' => $id, 'active' => 'Y'), '=');

		$stmt = $selectStatement->execute();
		$data = $stmt->fetch();

		if($data){
			$response['empresa'] = $this->parser_fecth($data);
			$response['result'] = true;
		}else{
			$response['error'] = 'Nenhuma empresa encontrada para essa ID.';
		}

		return $response;

	}

}

?>