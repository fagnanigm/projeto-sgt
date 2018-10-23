<?php

namespace Classes;

class Veiculos {

	private $db;

	public $item_per_page = 5;

	public $schema = array(
		"id_author",
		"id_filial",
		"veiculo_person",
		"veiculo_frota",
		"veiculo_tipo",
		"veiculo_status",
		"veiculo_placa",
		"veiculo_fabricante",
		"veiculo_modelo",
		"veiculo_ano_fab",
		"veiculo_ano_mod",
		"veiculo_antt",
		"veiculo_validade_antt",
		"veiculo_renavam",
		"veiculo_tipo_categoria",
		"veiculo_venc_tacografo",
		"veiculo_codesp_validade",
		"veiculo_codesp",
		"veiculo_seguro",
		"veiculo_st_financiamento",
		"veiculo_data_quitacao",
		"veiculo_beneficios",
		"veiculo_observacao",
		"create_time",
		"active",
		"veiculo_cidade_emplacamento",
		"veiculo_uf_emplacamento",
		"veiculo_num_contrato_financiamento",
		"veiculo_banco_financiamento",
		"veiculo_tipo_financiamento",
		"veiculo_quilometragem",
		"veiculo_ipva",
		"veiculo_ipva_isento",
		"veiculo_ipva_ano",
		"veiculo_seguro_obrigatorio",
		"veiculo_seguro_obrigatorio_ano",
		"veiculo_licenciado",
		"veiculo_licenciado_ano",
		"veiculo_permanente",
		"veiculo_permanente_ano",
		"veiculo_credenciado_prf",
		"veiculo_credenciado_prf_ano",
		"veiculo_emprestado_destino",
		"veiculo_manutencao_os",
		"veiculo_vendido_data",
		"veiculo_sinistro_data",
		"veiculo_viagem_as",
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

		if(!isset($args['veiculo_person'])){
			$response['error'] = 'O campo veiculo_person é obrigatório.';
			return $response;
		}

		if(!isset($args['veiculo_modelo'])){
			$response['error'] = 'O campo veiculo_modelo é obrigatório.';
			return $response;
		}

		if(!isset($args['veiculo_frota'])){
			$response['error'] = 'O campo veiculo_frota é obrigatório.';
			return $response;
		}

		if(!isset($args['veiculo_tipo'])){
			$response['error'] = 'O campo veiculo_tipo é obrigatório.';
			return $response;
		}	

		if(!isset($args['veiculo_status'])){
			$response['error'] = 'O campo veiculo_status é obrigatório.';
			return $response;
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
					$field == 'veiculo_data_quitacao' || 
					$field == 'veiculo_vendido_data' || 
					$field == 'veiculo_sinistro_data' || 
					$field == 'veiculo_codesp_validade'
				){
					$dt = new \DateTime();
					$dt->setTimestamp($val);
					$val = $dt->format("Y-m-d\TH:i:s");
				}

				if($field == 'veiculo_ipva'){
					$val = ($val ? 'Y' : 'N');
				}

				if($field == 'veiculo_ipva_isento'){
					$val = ($val ? 'Y' : 'N');
				}

				if($field == 'veiculo_seguro_obrigatorio'){
					$val = ($val ? 'Y' : 'N');
				}

				if($field == 'veiculo_licenciado'){
					$val = ($val ? 'Y' : 'N');
				}

				if($field == 'veiculo_permanente'){
					$val = ($val ? 'Y' : 'N');
				}

				if($field == 'veiculo_credenciado_prf'){
					$val = ($val ? 'Y' : 'N');
				}

				$data[$field] = $val;
			}else{
				unset($data[$field]);
			}

		}

		if($data['veiculo_ipva_isento'] == 'Y'){
			$data['veiculo_ipva'] = 'N';
			$data['veiculo_ipva_ano'] = '';
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
		$is_search = false;

		$args['getall'] = (isset($args['getall']) ? $args['getall'] : false);

		// Count total
		$query_count = "SELECT COUNT(*) AS total FROM veiculos WHERE active = 'Y'";

		// Filtro
		if(isset($args['veiculo_term'])){
			$query_count .= " AND ( ";
				$query_count .= "veiculo_frota LIKE '%".$args['veiculo_term']."%' OR ";
				$query_count .= "veiculo_modelo LIKE '%".$args['veiculo_term']."%' ";
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

			$select = $this->db->query('SELECT * FROM veiculos WHERE active = \'Y\' ORDER BY create_time');
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

				$query = "SELECT * FROM veiculos WHERE active = 'Y' ";

				// Filtro
				if(isset($args['veiculo_term'])){
					$query .= " AND ( ";
						$query .= "veiculo_frota LIKE '%".$args['veiculo_term']."%' OR ";
						$query .= "veiculo_modelo LIKE '%".$args['veiculo_term']."%' ";
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

		if(strlen($veiculo['veiculo_codesp_validade']) > 0){
			$create_time = new \DateTime($veiculo['veiculo_codesp_validade']);
			$veiculo['veiculo_codesp_validade_timestamp'] = $create_time->getTimestamp();
		}

		if(strlen($veiculo['veiculo_vendido_data']) > 0){
			$create_time = new \DateTime($veiculo['veiculo_vendido_data']);
			$veiculo['veiculo_vendido_data_timestamp'] = $create_time->getTimestamp();
		}

		if(strlen($veiculo['veiculo_sinistro_data']) > 0){
			$create_time = new \DateTime($veiculo['veiculo_sinistro_data']);
			$veiculo['veiculo_sinistro_data_timestamp'] = $create_time->getTimestamp();
		}

		$create_time = new \DateTime($veiculo['create_time']);
		$veiculo['create_timestamp'] = $create_time->getTimestamp();

		// Recursos
		$veiculo['veiculo_ipva'] = ($veiculo['veiculo_ipva'] == 'Y' ? true : false);
		$veiculo['veiculo_ipva_isento'] = ($veiculo['veiculo_ipva_isento'] == 'Y' ? true : false);
		$veiculo['veiculo_seguro_obrigatorio'] = ($veiculo['veiculo_seguro_obrigatorio'] == 'Y' ? true : false);
		$veiculo['veiculo_licenciado'] = ($veiculo['veiculo_licenciado'] == 'Y' ? true : false);
		$veiculo['veiculo_permanente'] = ($veiculo['veiculo_permanente'] == 'Y' ? true : false);
		$veiculo['veiculo_credenciado_prf'] = ($veiculo['veiculo_credenciado_prf'] == 'Y' ? true : false);

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
					$field == 'veiculo_data_quitacao' || 
					$field == 'veiculo_vendido_data' || 
					$field == 'veiculo_sinistro_data' ||
					$field == 'veiculo_codesp_validade'
				){
					$dt = new \DateTime();
					$dt->setTimestamp($val);
					$val = $dt->format("Y-m-d\TH:i:s");
				}

				if($field == 'veiculo_ipva'){
					$val = ($val ? 'Y' : 'N');
				}

				if($field == 'veiculo_ipva_isento'){
					$val = ($val ? 'Y' : 'N');
				}

				if($field == 'veiculo_seguro_obrigatorio'){
					$val = ($val ? 'Y' : 'N');
				}

				if($field == 'veiculo_licenciado'){
					$val = ($val ? 'Y' : 'N');
				}

				if($field == 'veiculo_permanente'){
					$val = ($val ? 'Y' : 'N');
				}

				if($field == 'veiculo_credenciado_prf'){
					$val = ($val ? 'Y' : 'N');
				}

				$data[$field] = $val;
			}else{
				unset($data[$field]);
			}

		}

		if($data['veiculo_ipva_isento'] == 'Y'){
			$data['veiculo_ipva'] = 'N';
			$data['veiculo_ipva_ano'] = '';
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
		
		$select = $this->db->query('SELECT * FROM veiculos 
			WHERE active = \'Y\' 
			AND (
				veiculo_frota = \''.$term.'\' OR
				veiculo_modelo LIKE \'%'.$term.'%\'
			)
			ORDER BY veiculo_frota');
			
		$response['results'] = $this->parser_fecth($select->fetchAll(\PDO::FETCH_ASSOC),'all');

		$response['result'] = true;

		return $response;

	}

}

?>