<?php

namespace Classes;

class Motoristas {

	private $db;

	public $item_per_page = 5;

	public $schema = array(
		"id_author",
		"id_filial",
		"motorista_person",
		"motorista_status",
		"motorista_nome",
		"motorista_cpf",
		"motorista_rg",
		"motorista_data_nascimento",
		"motorista_phone_01",
		"motorista_phone_02",
		"motorista_nextel",
		"motorista_cnh",
		"motorista_cnh_categoria",
		"motorista_cnh_validade",
		"motorista_cartao_pancary",
		"motorista_exame_rh",
		"motorista_exame_tox",
		"motorista_beneficios",
		"motorista_logradouro",
		"motorista_numero",
		"motorista_bairro",
		"motorista_complemento",
		"motorista_cidade",
		"motorista_estado",
		"motorista_observacao",
		"motorista_banco",
		"motorista_agencia",
		"motorista_cc",
		"motorista_banco_cpf",
		"motorista_nominal",
		"motorista_beneficio_nr10",
		"motorista_beneficio_nr35",
		"motorista_beneficio_ci",
		"motorista_beneficio_credenciado",
		"motorista_beneficio_vale_pedagio",
		"motorista_beneficio_ciot",
		"motorista_beneficio_codesp",
		"motorista_beneficio_codesp_validade",
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

		if(!isset($args['id_filial'])){
			$response['error'] = 'O campo id_filial é obrigatório.';
			return $response;
		}

		if(!isset($args['motorista_nome'])){
			$response['error'] = 'O campo motorista_nome é obrigatório.';
			return $response;
		}

		if(!isset($args['motorista_data_nascimento'])){
			$response['error'] = 'O campo motorista_data_nascimento é obrigatório.';
			return $response;
		}


		// Benefícios
		if(!isset($args['motorista_beneficio_nr10']) || strlen($args['motorista_beneficio_nr10']) == 0){
			$args['motorista_beneficio_nr10'] = false;
		}

		if(!isset($args['motorista_beneficio_nr35']) || strlen($args['motorista_beneficio_nr35']) == 0){
			$args['motorista_beneficio_nr35'] = false;
		}

		if(!isset($args['motorista_beneficio_ci']) || strlen($args['motorista_beneficio_ci']) == 0){
			$args['motorista_beneficio_ci'] = false;
		}

		if(!isset($args['motorista_beneficio_credenciado']) || strlen($args['motorista_beneficio_credenciado']) == 0){
			$args['motorista_beneficio_credenciado'] = false;
		}

		if(!isset($args['motorista_beneficio_vale_pedagio']) || strlen($args['motorista_beneficio_vale_pedagio']) == 0){
			$args['motorista_beneficio_vale_pedagio'] = false;
		}

		if(!isset($args['motorista_beneficio_ciot']) || strlen($args['motorista_beneficio_ciot']) == 0){
			$args['motorista_beneficio_ciot'] = false;
		}

		if(!isset($args['motorista_beneficio_codesp']) || strlen($args['motorista_beneficio_codesp']) == 0){
			$args['motorista_beneficio_codesp'] = false;
		}

		// Init insert
		$data = array_flip($this->schema);

		// External params
		foreach ($data as $field => $value) {
			
			if(isset($args[$field])){

				if(is_bool($args[$field]) || strlen($args[$field]) > 0){

					$val = $args[$field];

					// Tratamento de CPF
					if(
						$field == 'motorista_cpf' || 
						$field == 'motorista_banco_cpf' || 
						$field == 'motorista_rg'
					){
						$val = Utilities::unMask($val);
					}

					if(
						$field == 'motorista_cnh_validade' || 
						$field == 'motorista_data_nascimento' || 
						$field == 'motorista_beneficio_codesp_validade'
					){
						$date = new \DateTime();
						$date->setTimestamp($val);
						$val = $date->format("Y-m-d\TH:i:s");
					}

					// Checkboxes
					if(
						$field == 'motorista_beneficio_nr10' || 
						$field == 'motorista_beneficio_nr35' || 
						$field == 'motorista_beneficio_ci' || 
						$field == 'motorista_beneficio_credenciado' || 
						$field == 'motorista_beneficio_vale_pedagio' || 
						$field == 'motorista_beneficio_ciot' || 
						$field == 'motorista_beneficio_codesp'
					){
						$val = ($val ? 'Y' : 'N');
					}

					
					$data[$field] = $val;

				}else{
					unset($data[$field]);
				}

			}else{
				unset($data[$field]);
			}

		}

		// Fixed params
		$date = new \DateTime();
		$data['create_time'] = $date->format("Y-m-d\TH:i:s");

		$data['active'] = 'Y';

		$response['data'] = $data;

		$insertStatement = $this->db->insert(array_keys($data))->into('motoristas')->values(array_values($data));

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
		$query_count = "SELECT COUNT(*) AS total FROM motoristas WHERE active = 'Y'";

		// Filtro
		if(isset($args['motorista_term'])){
			$query_count .= " AND ( "; 
				$query_count .= "motorista_nome LIKE '%".$args['motorista_term']."%' OR ";
				$query_count .= "motorista_cpf LIKE '%".Utilities::unMask($args['motorista_term'])."%' ";
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

			$select = $this->db->query('SELECT * FROM motoristas WHERE active = \'Y\' ORDER BY create_time');
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

				$query = "SELECT * FROM motoristas WHERE active = 'Y' ";

				// Filtro
				if(isset($args['motorista_term'])){
					$query .= " AND ( "; 
						$query .= "motorista_nome LIKE '%".$args['motorista_term']."%' OR ";
						$query .= "motorista_cpf LIKE '%".Utilities::unMask($args['motorista_term'])."%' ";
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
			$result = $this->apply_filter_motorista($result);
		}else{
			if($fetch == 'all'){
				foreach ($result as $key => $value) {
					$result[$key] = $this->apply_filter_motorista($value);
				}
			}
		}
		return $result;
	}

	public function apply_filter_motorista($motorista){

		foreach ($motorista as $key => $field) {
			$motorista[$key] = trim($field);
		}

		if(strlen($motorista['motorista_cpf']) > 0){
			$motorista['motorista_cpf'] = Utilities::mask($motorista['motorista_cpf'],'###.###.###-##');
		}

		if(strlen($motorista['motorista_banco_cpf']) > 0){
			$motorista['motorista_banco_cpf'] = Utilities::mask($motorista['motorista_banco_cpf'],'###.###.###-##');
		}

		if(strlen($motorista['motorista_rg']) > 0){
			$motorista['motorista_rg'] = Utilities::mask($motorista['motorista_rg'],'##.###.###-#');
		}

		$create_time = new \DateTime($motorista['create_time']);
		$motorista['create_timestamp'] = $create_time->getTimestamp();

		$filial_st = $this->db->select(array('empresa_name'))->from('empresas')->whereMany( array('id' => $motorista['id_filial']), '=');
		$stmt = $filial_st->execute();
		$filial = $stmt->fetch();
		$motorista['filial_text'] = $filial['empresa_name'];
		

		// Checkbox
		$motorista['motorista_beneficio_nr10'] = ($motorista['motorista_beneficio_nr10'] == 'Y' ? true : false);
		$motorista['motorista_beneficio_nr35'] = ($motorista['motorista_beneficio_nr35'] == 'Y' ? true : false);
		$motorista['motorista_beneficio_ci'] = ($motorista['motorista_beneficio_ci'] == 'Y' ? true : false);
		$motorista['motorista_beneficio_credenciado'] = ($motorista['motorista_beneficio_credenciado'] == 'Y' ? true : false);
		$motorista['motorista_beneficio_vale_pedagio'] = ($motorista['motorista_beneficio_vale_pedagio'] == 'Y' ? true : false);
		$motorista['motorista_beneficio_ciot'] = ($motorista['motorista_beneficio_ciot'] == 'Y' ? true : false);
		$motorista['motorista_beneficio_codesp'] = ($motorista['motorista_beneficio_codesp'] == 'Y' ? true : false);

		return $motorista;
	}

	public function delete($args = array()){

		$response = array(
			'result' => false
		);

		if(!isset($args['id'])){
			$response['error'] = 'ID do motorista não especificado';
			return $response;
		}

		$updateStatement = $this->db->update(array('active' => 'N'))->table('motoristas')->where('id', '=', $args['id']);
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

		// Benefícios
		if(!isset($args['motorista_beneficio_nr10']) || strlen($args['motorista_beneficio_nr10']) == 0){
			$args['motorista_beneficio_nr10'] = false;
		}

		if(!isset($args['motorista_beneficio_nr35']) || strlen($args['motorista_beneficio_nr35']) == 0){
			$args['motorista_beneficio_nr35'] = false;
		}

		if(!isset($args['motorista_beneficio_ci']) || strlen($args['motorista_beneficio_ci']) == 0){
			$args['motorista_beneficio_ci'] = false;
		}

		if(!isset($args['motorista_beneficio_credenciado']) || strlen($args['motorista_beneficio_credenciado']) == 0){
			$args['motorista_beneficio_credenciado'] = false;
		}

		if(!isset($args['motorista_beneficio_vale_pedagio']) || strlen($args['motorista_beneficio_vale_pedagio']) == 0){
			$args['motorista_beneficio_vale_pedagio'] = false;
		}

		if(!isset($args['motorista_beneficio_ciot']) || strlen($args['motorista_beneficio_ciot']) == 0){
			$args['motorista_beneficio_ciot'] = false;
		}

		if(!isset($args['motorista_beneficio_codesp']) || strlen($args['motorista_beneficio_codesp']) == 0){
			$args['motorista_beneficio_codesp'] = false;
		}

		// Init insert
		$data = array_flip($this->schema);

		// External params
		foreach ($data as $field => $value) {
			
			if(isset($args[$field])){

				if(is_bool($args[$field]) || strlen($args[$field]) > 0){


					$val = $args[$field];

					// Tratamento de CPF
					if(
						$field == 'motorista_cpf' || 
						$field == 'motorista_banco_cpf' || 
						$field == 'motorista_rg'
					){
						$val = Utilities::unMask($val);
					}

					if(
						$field == 'motorista_cnh_validade' || 
						$field == 'motorista_data_nascimento' || 
						$field == 'motorista_beneficio_codesp_validade'
					){
						$date = new \DateTime();
						$date->setTimestamp($val);
						$val = $date->format("Y-m-d\TH:i:s");
					}

					// Checkboxes
					if(
						$field == 'motorista_beneficio_nr10' || 
						$field == 'motorista_beneficio_nr35' || 
						$field == 'motorista_beneficio_ci' || 
						$field == 'motorista_beneficio_credenciado' || 
						$field == 'motorista_beneficio_vale_pedagio' || 
						$field == 'motorista_beneficio_ciot' || 
						$field == 'motorista_beneficio_codesp'
					){
						$val = ($val ? 'Y' : 'N');
					}

					$data[$field] = $val;

				}else{
					unset($data[$field]);
				}

			}else{
				unset($data[$field]);
			}

		}

		$response['data'] = $data;

		$updateStatement = $this->db->update()->set($data)->table('motoristas')->whereMany( array('id' => $id), '=');

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

		$selectStatement = $this->db->select()->from('motoristas')->whereMany(array('id' => $id, 'active' => 'Y' ), '=');

		$stmt = $selectStatement->execute();
		$data = $stmt->fetch();

		if($data){
			$response['motorista'] = $this->parser_fecth($data);
			$response['result'] = true;
		}else{
			$response['error'] = 'Nenhum motorista encontrado para essa ID.';
		}

		return $response;

	}

	public function search($args = array()){

		$response = array('result' => false);

		if(!isset($args['term'])){
			$response['error'] = 'Termo não definido.';
			return $response;
		}else{
			if(strlen(trim($args['term'])) == 0){
				$response['error'] = 'Termo não definido.';
				return $response;
			}
		}

		$term = trim($args['term']);
		
		$select = $this->db->query('SELECT * FROM motoristas 
			WHERE active = \'Y\' 
			AND (
				motorista_nome LIKE \'%'.$term.'%\'
			)
			ORDER BY motorista_nome');
			
		$response['results'] = $this->parser_fecth($select->fetchAll(\PDO::FETCH_ASSOC),'all');

		$response['result'] = true;

		return $response;

	}

}

?>