<?php

namespace Classes;

use \Firebase\JWT\JWT;

class User {

	private $db;

	public $schema = array(
		"username",
		"email",
		"ddd_phone_01",
		"phone_01",
		"ddd_phone_02",
		"phone_02",
		"password",
		"person" ,
		"cpf",
		"cnpj",
		"create_time",
		"permission",
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

		if(!isset($args['email'])){
			$response['error'] = 'O campo email é obrigatório.';
			return $response;
		}

		if(!isset($args['username'])){
			$response['error'] = 'O campo username é obrigatório.';
			return $response;
		}

		if(!isset($args['password'])){
			$response['error'] = 'O campo password é obrigatório.';
			return $response;
		}

		if($this->check_email($args['email'])){
			$response['error'] = 'E-mail já utilizado.';
			return $response;
		}

		// Init insert

		$data = array_flip($this->schema);

		// External params
		foreach ($data as $field => $value) {
			
			if(isset($args[$field])){

				$val = $args[$field];

				// Tratamento de senha
				if($field == 'password'){
					$val = Bcrypt::hash($val);
				}

				// Tratamento de CPF
				if($field == 'cpf'){
					$val = Utilities::unMask($val);
				}

				// Tratamento de CNPJ
				if($field == 'cnpj'){
					$val = Utilities::unMask($val);
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

		$insertStatement = $this->db->insert(array_keys($data))->into('users')->values(array_values($data));
		$response['id'] = $insertStatement->execute();

		if(strlen($response['id']) > 0){
			$response['result'] = true;
		}

		return $response;

	}

	public function check_email($email){

		$selectStatement = $this->db->select()->from('users')->where('email', '=', $email);
		$stmt = $selectStatement->execute();
		$data = $stmt->fetch();

		return (!$data ? false : true);

	}

	public function get($args = array()){

		$response = array();
		$is_search = false;

		// Count total
		$query_count = "SELECT COUNT(*) AS total FROM users WHERE active = 'Y'";

		// Filtro
		if(isset($args['user_term'])){
			$query_count .= " AND (";
				$query_count .= "username LIKE '%".$args['user_term']."%' OR ";
				$query_count .= "email LIKE '%".$args['user_term']."%' OR ";
				$query_count .= "cpf LIKE '%".Utilities::unMask($args['user_term'])."%' ";
			$query_count .= ") ";
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

			$query = "SELECT * FROM users WHERE active = 'Y' ";

			// Filtro
			if(isset($args['user_term'])){
				$query .= " AND (";
					$query .= "username LIKE '%".$args['user_term']."%' OR ";
					$query .= "email LIKE '%".$args['user_term']."%' OR ";
					$query .= "cpf LIKE '%".Utilities::unMask($args['user_term'])."%' ";
				$query .= ") ";
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
			$result = $this->apply_filter_user($result);
		}else{
			if($fetch == 'all'){
				foreach ($result as $key => $value) {
					$result[$key] = $this->apply_filter_user($value);
				}
			}
		}
		return $result;
	}

	public function apply_filter_user($user){
		unset($user['password']);
		foreach ($user as $key => $field) {
			$user[$key] = trim($field);
		}
		if($user['person'] == 'f'){
			if(strlen($user['cpf']) > 0){
				$user['cpf'] = Utilities::mask($user['cpf'],'###.###.###-##');
			}
		}else{
			if(strlen($user['cnpj']) > 0){
				$user['cnpj'] = Utilities::mask($user['cnpj'],'##.###.###/####-##');
			}
		}

		$create_time = new \DateTime($user['create_time']);
		$user['create_timestamp'] = $create_time->getTimestamp();

		return $user;
	}

	public function delete($args = array()){

		$response = array(
			'result' => false
		);

		if(!isset($args['id'])){
			$response['error'] = 'ID do usuário não especificado';
			return $response;
		}

		$updateStatement = $this->db->update(array('active' => 'N'))->table('users')->where('id', '=', $args['id']);
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

		// Tratamento de CPF
		if(isset($args['cpf'])){
			$args['cpf'] = Utilities::unMask($args['cpf']);
		}

		// Tratamento de CNPJ
		if(isset($args['cnpj'])){
			$args['cnpj'] = Utilities::unMask($args['cnpj']);
		}

		// Senha
		if(isset($args['password'])){
			unset($args['password']);
		}

		// Timestamp
		if(isset($args['create_timestamp'])){
			unset($args['create_timestamp']);
		}

		$updateStatement = $this->db->update()->set($args)->table('users')->where('id', '=', $id);

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

	public function login($args = array()){

		$response = array(
			'result' => false
		);

		if(!isset($args['email'])){
			$response['error'] = 'E-mail não informado.';
			return $response;
		}

		if(!isset($args['password'])){
			$response['error'] = 'Senha não informada.';
			return $response;
		}else{
			$args['hash'] = Bcrypt::hash($args['password']);
		}

		$selectStatement = $this->db->select()->from('users')->whereMany( array('email' => $args['email'], 'active' => 'Y'),'=');
		$stmt = $selectStatement->execute();
		$user = $stmt->fetch();

		if($user){
			if (Bcrypt::check($args['password'], trim($user['password'])  )) {
				$response['result'] = true;
				$response['user'] = $this->parser_fecth($user);

				$token = array(
				    "iss" => "http://201.26.16.228/api",
				    "aud" => "http://201.26.16.228",
				    "iat" => time(),
				    "nbf" => 0,
				    "sub" => $user['id']
				);

				$response['token'] = JWT::encode($token, AUTHENTICATE_KEY);

				// $response['untoken'] = JWT::decode($response['token'], AUTHENTICATE_KEY, array('HS256'));

			}else{
				$response['error'] = 'Senha incorreta.';
			}
		}else{
			$response['error'] = 'Usuário não encontrado.';
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

		$selectStatement = $this->db->select()->from('users')->whereMany( array( 'id' => $id, 'active' => 'Y'), '=') ;

		$stmt = $selectStatement->execute();
		$data = $stmt->fetch();

		if($data){
			$response['user'] = $this->parser_fecth($data);
			$response['result'] = true;
		}else{
			$response['error'] = 'Nenhum usuário encontrado para essa ID.';
		}

		return $response;

	}

	public function change_pass($args = array()){

		$response = array(
			'result' => false
		);

		if(!isset($args['id'])){
			$response['error'] = 'ID do usuário não informado';
			return $response;
		}else{

			// checa se id do usuário existe
			$userSt = $this->db->select()->from('users')->whereMany(array('id' => $args['id'], 'active' => 'Y'), '=');
			$stmt = $userSt->execute();
			$user_data = $stmt->fetch();

			if(!$user_data){
				$response['error'] = 'Usuário informado não existente.';
				return $response;
			}

		}

		if(!isset($args['pass'])){
			$response['error'] = 'Senha não informada';
			return $response;
		}else{
			$args['pass'] = Bcrypt::hash($args['pass']);
		}

		$updateStatement = $this->db->update()->set(array('password' => $args['pass']))->table('users')->where('id', '=', $args['id']);

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

	public function user_change_pass($args){

		$response = array(
			'result' => false
		);

		if(!isset($args['active_pass'])){
			$response['error'] = 'Senha atual não informada';
			return $response;
		}

		if(!isset($args['pass'])){
			$response['error'] = 'Senha não informada';
			return $response;
		}

		if(!isset($args['id'])){
			$response['error'] = 'ID do usuário não informado';
			return $response;
		}else{

			// checa se id do usuário existe
			$userSt = $this->db->select()->from('users')->whereMany(array('id' => $args['id'], 'active' => 'Y'), '=');
			$stmt = $userSt->execute();
			$user_data = $stmt->fetch();

			if(!$user_data){
				$response['error'] = 'Usuário informado não existente.';
				return $response;
			}else{

				if (Bcrypt::check($args['active_pass'], trim($user_data['password'])  )) {

					$response = $this->change_pass(array(
						'id' => $args['id'],
						'pass' => $args['pass']
					));

					return $response;

				}else{
					$response['error'] = 'Senha atual incorreta.';
					return $response;
				}

			}

		}


	}

}

?>