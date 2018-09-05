<?php

namespace Classes;

class Logs {

	private $db;

	public $item_per_page = 5;

	public $schema = array(
		"id_user",
		"id_target",
		"log_modulo",
		"log_descricao",
		"log_content",
		"log_ip",
		"create_time",
		"log_tool",
		"log_result"
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

		// Init insert
		$data = array_flip($this->schema);

		// External params
		foreach ($data as $field => $value) {
			
			if(isset($args[$field])){
				$val = $args[$field];

				if($field == 'log_content'){

					if(!is_string($val)){
						$val = json_encode($val, JSON_UNESCAPED_SLASHES);
					}

				}	

				if($field == 'log_result'){	
					$val = ($val ? '1' : '0');
				}	

				$data[$field] = $val;
			}else{
				unset($data[$field]);
			}

		}

		// Fixed params
		$date = new \DateTime();
		$data['create_time'] = $date->format("Y-m-d\TH:i:s");

		$insertStatement = $this->db->insert(array_keys($data))->into('logs')->values(array_values($data));

		$response['id'] = $insertStatement->execute();

		if(strlen($response['id']) > 0){
			$response['result'] = true;
		}

		return $response;

	}


	public function get($args = array()){

		$response = array();

		$args['getall'] = (isset($args['getall']) ? $args['getall'] : false);

		// Count total
		$selectStatement = $this->db->select(array('COUNT(*) AS total'))->from('veiculos')->whereMany(array('active' => 'Y'),'=');
		$stmt = $selectStatement->execute();
		$total_data = $stmt->fetch();

		if($args['getall'] == '1'){

			$config = array(
				'total' => $total_data['total'],
			);

			$response['config'] = $config;

			$select = $this->db->query('SELECT * FROM veiculos WHERE active = \'Y\' ORDER BY create_time');
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
				$select = $this->db->query('SELECT * FROM veiculos WHERE active = \'Y\' ORDER BY create_time OFFSET '.$offset.' ROWS FETCH NEXT '.$config['item_per_page'].' ROWS ONLY');
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
			$result = $this->apply_filter_veiculo($result);
		}else{
			if($fetch == 'all'){
				foreach ($result as $key => $value) {
					$result[$key] = $this->apply_filter_veiculo($value);
				}
			}
		}
		return $result;
	}

	public function apply_filter_veiculo($veiculo){

		foreach ($veiculo as $key => $field) {
			$veiculo[$key] = trim($field);
		}
		
		if(strlen($veiculo['veiculo_data_quitacao']) > 0){
			$create_time = new \DateTime($veiculo['veiculo_data_quitacao']);
			$veiculo['veiculo_data_quitacao_timestamp'] = $create_time->getTimestamp();
		}

		if(strlen($veiculo['veiculo_validade_antt']) > 0){
			$create_time = new \DateTime($veiculo['veiculo_validade_antt']);
			$veiculo['veiculo_validade_antt_timestamp'] = $create_time->getTimestamp();
		}

		if(strlen($veiculo['veiculo_venc_tacografo']) > 0){
			$create_time = new \DateTime($veiculo['veiculo_venc_tacografo']);
			$veiculo['veiculo_venc_tacografo_timestamp'] = $create_time->getTimestamp();
		}

		$create_time = new \DateTime($veiculo['create_time']);
		$veiculo['create_timestamp'] = $create_time->getTimestamp();

		
		$filial_st = $this->db->select(array('empresa_name'))->from('empresas')->whereMany( array('id' => $veiculo['id_filial']), '=');
		$stmt = $filial_st->execute();
		$filial = $stmt->fetch();
		$veiculo['filial_text'] = $filial['empresa_name'];
		
		return $veiculo;
	}

	public function delete($args = array()){

		$response = array(
			'result' => false
		);

		if(!isset($args['id'])){
			$response['error'] = 'ID do veículo não especificado';
			return $response;
		}

		$updateStatement = $this->db->update(array('active' => 'N'))->table('veiculos')->where('id', '=', $args['id']);
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

				if(
					$field == 'veiculo_validade_antt' || 
					$field == 'veiculo_venc_tacografo' || 
					$field == 'veiculo_data_quitacao' 
				){
					$dt = new \DateTime();
					$dt->setTimestamp($val);
					$val = $dt->format("Y-m-d\TH:i:s");
				}

				$data[$field] = $val;
			}else{
				unset($data[$field]);
			}

		}
		
		$updateStatement = $this->db->update()->set($data)->table('veiculos')->whereMany( array('id' => $id), '=');

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

		$selectStatement = $this->db->select()->from('veiculos')->whereMany(array('id' => $id, 'active' => 'Y' ), '=');

		$stmt = $selectStatement->execute();
		$data = $stmt->fetch();

		if($data){
			$response['veiculo'] = $this->parser_fecth($data);
			$response['result'] = true;
		}else{
			$response['error'] = 'Nenhum veículo encontrado para essa ID.';
		}

		return $response;

	}

}

?>