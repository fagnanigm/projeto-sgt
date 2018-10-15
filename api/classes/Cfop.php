<?php

namespace Classes;

class Cfop {

	private $db;

	public $item_per_page = 5;

	public $schema = array(
		"cfop_descricao",
		"cfop_observacao",
		"cfop_tipo",
		"cfop_codigo",
		"cfop_origem",
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

		if(!isset($args['cfop_codigo'])){
			$response['error'] = 'O campo cfop_codigo é obrigatório.';
			return $response;
		}

		if(!isset($args['cfop_descricao'])){
			$response['error'] = 'O campo cfop_descricao é obrigatório.';
			return $response;
		}

		if(!isset($args['cfop_origem'])){
			$args['cfop_origem'] = 'api';
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

		$insertStatement = $this->db->insert(array_keys($data))->into('lista_cfop')->values(array_values($data));

		$response['id'] = $insertStatement->execute();

		if(strlen($response['id']) > 0){
			$response['result'] = true;
		}

		return $response;

	}


	public function get($args = array()){

		$response = array();

		$args['getall'] = (isset($args['getall']) ? $args['getall'] : false);

		$is_search = false;

		// Count total
		$query_count = "SELECT COUNT(*) AS total FROM lista_cfop WHERE active = 'Y'";

		// Filtro


		$count = $this->db->query($query_count);
		$total_data = $count->fetch();

		if($args['getall'] == '1'){

			$config = array(
				'total' => $total_data['total'],
			);

			$response['config'] = $config;

			$select = $this->db->query('SELECT * FROM lista_cfop WHERE active = \'Y\' ORDER BY create_time');
			$response['results'] = $this->parser_fetch($select->fetchAll(\PDO::FETCH_ASSOC),'all');

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

				$query = "SELECT * FROM lista_cfop WHERE active = 'Y' ";

				// Filtro


				// Config
				$query .= "ORDER BY create_time OFFSET ".$offset." ROWS FETCH NEXT ".$config['item_per_page']." ROWS ONLY";

				$select = $this->db->query($query);
				$response['results'] = $this->parser_fetch($select->fetchAll(\PDO::FETCH_ASSOC),'all');
				$response['config']['page_items_total'] = count($response['results']);

			}else{
				$response['results'] = [];
			}

		}

		return $response;

	}

	public function parser_fetch($result, $fetch = 'one'){
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
			$response['error'] = 'ID do CFOP não especificado';
			return $response;
		}

		$updateStatement = $this->db->update(array('active' => 'N'))->table('lista_cfop')->where('id', '=', $args['id']);
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

		$updateStatement = $this->db->update()->set($data)->table('lista_cfop')->whereMany( array('id' => $id ), '=');

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

		$selectStatement = $this->db->select()->from('lista_cfop')->whereMany(array('id' => $id, 'active' => 'Y' ), '=');

		$stmt = $selectStatement->execute();
		$data = $stmt->fetch();

		if($data){
			$response['data'] = $this->parser_fetch($data);
			$response['result'] = true;
		}else{
			$response['error'] = 'Nenhum CFOP encontrado para essa ID.';
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
		
		$select = $this->db->query('SELECT * FROM lista_cfop 
			WHERE active = \'Y\' 
			AND (
				cfop_descricao LIKE \'%'.$term.'%\' OR
				cfop_codigo = \''.$term.'\'
			)
			ORDER BY cfop_codigo');
			
		$response['results'] = $this->parser_fetch($select->fetchAll(\PDO::FETCH_ASSOC),'all');

		$response['result'] = true;

		return $response;

	}

	public function importOmie($args = false){

		$response = array(
			'result' => true
		);

		if(!isset($args['app_key'])){
			$response['error'] = 'O campo app_key é obrigatório';
			return $response;
		}

		if(!isset($args['app_secret'])){
			$response['error'] = 'O campo app_secret é obrigatório';
			return $response;
		}

		$start_curl = $this->omieCurl(1,$args['app_key'],$args['app_secret']);

		$results = $start_curl->cadastros;

		$count_insert = 0;
		$count_error = 0;
		$errors_data = array();
		$count_repeats = 0;
		$count_updates = 0;

		for ($page = 2; $page <= $start_curl->total_de_paginas; $page++) { 
			$other_pages = $this->omieCurl($page,$args['app_key'],$args['app_secret']);
			$results = array_merge($results, $other_pages->cadastros);
		}

		foreach ($results as $key => $cfop) {	

			$param = array(
				"cfop_descricao" => $cfop->cDescricao,
				"cfop_observacao" => $cfop->cObservacao,
				"cfop_tipo" => $cfop->cTipo,
				"cfop_codigo" => $cfop->nCodigo,
				"cfop_origem" => "omie"
			);

			$this->insert($param);
			

		}

		return $response;


	}

	public function omieCurl($page = 1, $app_key, $app_secret){

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://app.omie.com.br/api/v1/produtos/cfop/",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "{\"call\":\"ListarCFOP\",\"app_key\":\"".$app_key."\",\"app_secret\":\"".$app_secret."\",\"param\":[{\"pagina\":".$page.",\"registros_por_pagina\":50}]}",
			CURLOPT_HTTPHEADER => array(
				"Content-Type: application/json",
				"Postman-Token: 7c1a2b3f-e180-44ba-a2fe-f0953250aab7",
				"cache-control: no-cache"
			),
		));

		$response = json_decode(curl_exec($curl));
		$err = curl_error($curl);

		curl_close($curl);

		return $response;

	}



}

?>