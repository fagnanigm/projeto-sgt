<?php

namespace Classes;

class Veiculos {

	private $db;

	public $item_per_page = 5;

	public $schema = array(
		"id_author",
		"id_empresa",
		"veiculo_person",
		"veiculo_frota",
		"veiculo_filial",
		"veiculo_status",
		"veiculo_placa",
		"veiculo_fabricante",
		"veiculo_modelo",
		"veiculo_ano_fab",
		"veiculo_ano_mod",
		"veiculo_antt",
		"veiculo_validade_antt",
		"veiculo_renavam",
		"veiculo_tipo",
		"veiculo_venc_tacografo",
		"veiculo_codesp",
		"veiculo_seguro",
		"veiculo_st_financiamento",
		"veiculo_data_quitacao",
		"veiculo_observacao",
		"veiculo_beneficios",
		"veiculo_st_data_saida",
		"veiculo_st_motorista",
		"veiculo_st_rv",
		"veiculo_st_ctrc",
		"veiculo_st_as",
		"veiculo_st_cliente",
		"veiculo_st_uf_destino",
		"veiculo_manutencoes",
		"veiculo_registro_pneus",
		"veiculo_custo_total_pecas",
		"veiculo_custo_serv_terceiros",
		"veiculo_custo_mao_obra",
		"veiculo_custo_manutencoes",
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

		if(!isset($args['id_empresa'])){
			$response['error'] = 'O campo id_empresa é obrigatório.';
			return $response;
		}

		if(!isset($args['veiculo_person'])){
			$response['error'] = 'O campo veiculo_person é obrigatório.';
			return $response;
		}

		if(!isset($args['veiculo_modelo'])){
			$response['error'] = 'O campo veiculo_modelo é obrigatório.';
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

		$insertStatement = $this->db->insert(array_keys($data))->into('veiculos')->values(array_values($data));

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
		$selectStatement = $this->db->select(array('COUNT(*) AS total'))->from('veiculos')->whereMany(array('active' => 'Y', 'id_empresa' => $args['context']),'=');
		$stmt = $selectStatement->execute();
		$total_data = $stmt->fetch();

		if($args['getall'] == '1'){

			$config = array(
				'total' => $total_data['total'],
			);

			$response['config'] = $config;

			$select = $this->db->query('SELECT * FROM veiculos WHERE id_empresa = \''.$args['context'].'\' AND active = \'Y\' ORDER BY create_time');
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
				$select = $this->db->query('SELECT * FROM veiculos WHERE id_empresa = \''.$args['context'].'\' AND active = \'Y\' ORDER BY create_time OFFSET '.$offset.' ROWS FETCH NEXT '.$config['item_per_page'].' ROWS ONLY');
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

	public function apply_filter_veiculo($local){

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

		$updateStatement = $this->db->update()->set($args)->table('veiculos')->whereMany( array('id' => $id, 'id_empresa' => $context), '=');

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

		$selectStatement = $this->db->select()->from('veiculos')->whereMany(array('id' => $id, 'active' => 'Y', 'id_empresa' => $args['context'] ), '=');

		$stmt = $selectStatement->execute();
		$data = $stmt->fetch();

		if($data){
			$response['local'] = $this->parser_fecth($data);
			$response['result'] = true;
		}else{
			$response['error'] = 'Nenhum veículo encontrado para essa ID.';
		}

		return $response;

	}

}

?>