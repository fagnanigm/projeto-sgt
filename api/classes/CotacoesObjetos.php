<?php

namespace Classes;

class CotacoesObjetos {

	private $db;

	public $schema = array(
		"id_cotacao",
		"objeto_item",
		"objeto_quantidade",
		"objeto_descricao",
		"objeto_origem",
		"objeto_destino",
		"objeto_quilometragem",
		"objeto_comprimento",
		"objeto_largura",
		"objeto_altura",
		"objeto_peso_unit",
		"objeto_peso_total",
		"objeto_tipo_valor",
		"objeto_valor_unit",
		"objeto_valor_total",
		"create_time",
		"active"
	);

	function __construct($db = false){
		if(!$db){
			die();
		}
		$this->db = $db;
	}

	public function insert($args = array()){

		$response = array(
			'result' => false
		);

		// Block if required fields isn't informed
		if(!isset($args['id_cotacao'])){
			$response['error'] = 'O campo id_cotacao é obrigatório.';
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

		$insertStatement = $this->db->insert(array_keys($data))->into('cotacoes_objetos')->values(array_values($data));

		$response['id'] = $insertStatement->execute();

		if(strlen($response['id']) > 0){
			$response['result'] = true;
		}

		return $response;

	}


	public function get($args = array()){

		$response = array();

		if(!isset($args['id_cotacao'])){
			$response['error'] = 'O campo id_cotacao é obrigatório.';
			return $response;
		}

		$query = "SELECT * FROM cotacoes_objetos WHERE id_cotacao = '".$args['id_cotacao']."';";
					
		$select = $this->db->query($query);
		$response['results'] = $this->parser_fetch($select->fetchAll(\PDO::FETCH_ASSOC),'all');

		return $response;

	}

	public function parser_fetch($result, $fetch = 'one'){
		if($fetch == 'one'){
			$result = $this->apply_filter_cotacao($result);
		}else{
			if($fetch == 'all'){
				foreach ($result as $key => $value) {
					$result[$key] = $this->apply_filter_cotacao($value);
				}
			}
		}
		return $result;
	}

	public function apply_filter_cotacao($cotacao){

		foreach ($cotacao as $key => $field) {
			$cotacao[$key] = trim($field);
		}

		$cadastro_data = new \DateTime($cotacao['cotacao_cadastro_data']);
		$cotacao['cotacao_cadastro_data'] = $cadastro_data->getTimestamp();

		$create_time = new \DateTime($cotacao['create_time']);
		$cotacao['create_timestamp'] = $create_time->getTimestamp();

		// Checkboxes
		$cotacao['cotacao_vi_imposto_icms'] = ($cotacao['cotacao_vi_imposto_icms'] == 'Y' ? true : false);
		$cotacao['cotacao_vi_imposto_inss'] = ($cotacao['cotacao_vi_imposto_inss'] == 'Y' ? true : false);
		$cotacao['cotacao_vi_imposto_ir'] = ($cotacao['cotacao_vi_imposto_ir'] == 'Y' ? true : false);
		$cotacao['cotacao_vi_taxas'] = ($cotacao['cotacao_vi_taxas'] == 'Y' ? true : false);
		$cotacao['cotacao_vi_iss'] = ($cotacao['cotacao_vi_iss'] == 'Y' ? true : false);
		$cotacao['cotacao_vi_escolta'] = ($cotacao['cotacao_vi_escolta'] == 'Y' ? true : false);
		$cotacao['cotacao_vi_imposto_pis_cofins'] = ($cotacao['cotacao_vi_imposto_pis_cofins'] == 'Y' ? true : false);
		$cotacao['cotacao_vi_seguro'] = ($cotacao['cotacao_vi_seguro'] == 'Y' ? true : false);

		$cotacao['cotacao_status_text'] = $this->cotacao_status_array[$cotacao['cotacao_status']];

		$cotacao['cotacao_caracteristica_objetos'] = array();

		return $cotacao;
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

		unset($args['create_timestamp']);

		// Tratamento

		if(isset($args['cotacao_comp'])){
			if(strlen(trim($args['cotacao_comp'])) == 0){
				unset($args['cotacao_comp']);
			}
		}

		if(isset($args['cotacao_larg'])){
			if(strlen(trim($args['cotacao_larg'])) == 0){
				unset($args['cotacao_larg']);
			}
		}

		if(isset($args['cotacao_alt'])){
			if(strlen(trim($args['cotacao_alt'])) == 0){
				unset($args['cotacao_alt']);
			}
		}

		if(isset($args['cotacao_valor_un'])){
			if(strlen(trim($args['cotacao_valor_un'])) == 0){
				unset($args['cotacao_valor_un']);
			}
		}

		if(isset($args['cotacao_valor_total'])){
			if(strlen(trim($args['cotacao_valor_total'])) == 0){
				unset($args['cotacao_valor_total']);
			}
		}

		if(isset($args['cotacao_peso_un'])){
			if(strlen(trim($args['cotacao_peso_un'])) == 0){
				unset($args['cotacao_peso_un']);
			}
		}

		if(isset($args['cotacao_peso_total'])){
			if(strlen(trim($args['cotacao_peso_total'])) == 0){
				unset($args['cotacao_peso_total']);
			}
		}

		$updateStatement = $this->db->update()->set($args)->table('cotacoes')->whereMany( array('id' => $id), '=');

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

		$selectStatement = $this->db->query("
			SELECT c.*, e.empresa_name, v.vendedor_nome, cat.cat_name, fg.forma_nome
				FROM cotacoes c 

					INNER JOIN empresas e 
					ON c.id_empresa = e.id

					INNER JOIN vendedores v 
					ON c.id_vendedor = v.id

					INNER JOIN categorias cat 
					ON c.id_categoria = cat.id

					INNER JOIN formas_pagamento fg 
					ON c.id_forma_pagamento = fg.id

				WHERE c.id = '".$id."' AND c.active = 'Y'
		");

		$data = $selectStatement->fetch();

		if($data){
			$response['cotacao'] = $this->parser_fetch($data);
			$response['result'] = true;
		}else{
			$response['error'] = 'Nenhuma cotação encontrada para essa ID.';
		}

		return $response;

	}

	public function getnextcode($args){

		$response = array(
			'result' => false
		);

		if(!isset($args['id_empresa'])){
			$response['error'] = 'O campo id_empresa é obrigatório';
			return $response;
		}else{

			$select = $this->db->select()->from('empresas')->whereMany(array('active' => 'Y', 'id' => $args['id_empresa']), '=');
			$stmt = $select->execute();
			$empresa = $stmt->fetch();

			if(!$empresa){
				$response['error'] = 'Empresa não encontrada';
				return $response;
			}

		}

		if(!isset($args['revisao'])){
			$response['error'] = 'O campo revisao é obrigatório';
			return $response;
		}else{
			$args['revisao'] = str_pad(abs($args['revisao']), 2, '0', STR_PAD_LEFT);
		}

		if(isset($args['cotacao_code_sequencial'])){
			$sequencial = $args['cotacao_code_sequencial'];
		}else{
			$select = $this->db->query('SELECT max(cotacao_code_sequencial) AS code FROM cotacoes;');
			$max_code_sequencial = $select->fetch(\PDO::FETCH_ASSOC);
			$sequencial = str_pad(($max_code_sequencial['code'] + 1), 5, '0', STR_PAD_LEFT);
		}

		$response['cotacao_code'] = 
			$empresa['empresa_prefixo'] . '-' .
			date('m') . '-' .
			$sequencial . '/' .
			date('y') . '-' .
			'rev.' . $args['revisao'];

		$response['cotacao_code_sequencial'] = $sequencial;

		$response['result'] = true;

		return $response;

	}

	public function get_sequencial_from_code($code){

		$code = explode("-", $code);

		if(isset($code[2])){
			$piece = explode('/', $code[2]);
			return $piece[0];
		}else{
			return false;
		}

	}

	public function get_revisoes($id_cotacao = false){

		$response = array(
			'result' => false
		);

		if(!$id_cotacao){
			$response['error'] = 'ID da cotação não informado';
			return $response;
		}else{

			$cotacao = $this->get_by_id($id_cotacao);

			if(!$cotacao['result']){
				$response['error'] = $cotacao['error'];
				return $response;
			}else{
				$cotacao = $cotacao['cotacao'];
			}

		}

		$select = $this->db->query("SELECT * FROM cotacoes WHERE id_revisao = '".$cotacao['id_revisao']."' AND id != '".$cotacao['id']."' AND active = 'Y' ORDER BY create_time");
		$revisoes = $this->parser_fetch($select->fetchAll(\PDO::FETCH_ASSOC),'all');

		// ------------------------------

		$response['revisoes'] = $revisoes;
		$response['cotacao'] = $cotacao;
		$response['result'] = true;

		return $response;

	}

}

?>