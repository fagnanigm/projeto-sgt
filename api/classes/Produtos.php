<?php

namespace Classes;

class Produtos {

	private $db;

	public $schema = array( 
		"id_author",
		"id_empresa",
		"produto_name",
		"produto_descricao",
		"produto_codigo_cest",
		"produto_codigo_omie",
		"produto_ean",
		"produto_ncm",
		"produto_origem",
		"produto_unidade", 
		"produto_valor",
		"create_time",
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

		if(!isset($args['id_author'])){
			$response['error'] = 'O campo id_author é obrigatório.';
			return $response;
		}

		if(!isset($args['id_empresa'])){
			$response['error'] = 'O campo id_empresa é obrigatório.';
			return $response;
		}

		if(!isset($args['produto_name'])){
			$response['error'] = 'O campo produto_name é obrigatório.';
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

		$insertStatement = $this->db->insert(array_keys($data))->into('produtos')->values(array_values($data));
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

		// Count total
		$selectStatement = $this->db->select(array('COUNT(*) AS total'))->from('produtos')->whereMany(array('active' => 'Y', 'id_empresa' => $args['context']), '=' );
		$stmt = $selectStatement->execute();
		$total_data = $stmt->fetch();

		$config = array(
			'total' => $total_data['total'],
			'item_per_page' => $this->item_per_page,
			'total_pages' => ceil($total_data['total'] / $this->item_per_page),
			'current_page' => (isset($args['current_page']) ? $args['current_page'] : 1 )
		);

		$response['config'] = $config;

		if($config['current_page'] <= $config['total_pages']){

			$offset = ($config['current_page'] == '1' ? 0 : ($config['current_page'] - 1) * $config['item_per_page'] );
			$select = $this->db->query('SELECT * FROM produtos WHERE active = \'Y\' AND id_empresa = \''.$args['context'].'\' ORDER BY create_time OFFSET '.$offset.' ROWS FETCH NEXT '.$config['item_per_page'].' ROWS ONLY');
			$response['results'] = $this->parser_fecth($select->fetchAll(\PDO::FETCH_ASSOC),'all');
			$response['config']['page_items_total'] = count($response['results']);

		}else{
			$response['results'] = [];
		}

		return $response;

	}

	public function parser_fecth($result, $fetch = 'one'){
		if($fetch == 'one'){
			$result = $this->apply_filter_produto($result);
		}else{
			if($fetch == 'all'){
				foreach ($result as $key => $value) {
					$result[$key] = $this->apply_filter_produto($value);
				}
			}
		}
		return $result;
	}

	public function apply_filter_produto($produto){
		
		foreach ($produto as $key => $field) {
			$produto[$key] = trim($field);
		}
		
		return $produto;
	}

	public function delete($args = array()){

		$response = array(
			'result' => false
		);

		if(!isset($args['id'])){
			$response['error'] = 'ID do produto não especificado';
			return $response;
		}

		$updateStatement = $this->db->update(array('active' => 'N'))->table('produtos')->where('id', '=', $args['id']);
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

		$selectStatement = $this->db->select()->from('produtos')->whereMany(array('id' => $id, 'id_empresa' => $args['context'], 'active' => 'Y'), '=' );

		$stmt = $selectStatement->execute();
		$data = $stmt->fetch();

		if($data){
			$response['produto'] = $this->parser_fecth($data);
			$response['result'] = true;
		}else{
			$response['error'] = 'Nenhum produto encontrado para essa ID.';
		}

		return $response;

	}

	public function importOmie($args = false){

		$response = array(
			'result' => true
		);

		if(!isset($args['context'])){
			$response = array(
				'result' => false,
				'error' => 'Contexto não definido'
			);
			return $response;
		}

		$start_curl = $this->omieCurl(1);

		$results = $start_curl->produto_servico_cadastro;

		$count_insert = 0;
		$count_error = 0;
		$errors_data = array();
		$count_repeats = 0;

		for ($page = 2; $page <= $start_curl->total_de_paginas; $page++) { 
			$other_pages = $this->omieCurl($page);
			$results = array_merge($results, $other_pages->produto_servico_cadastro);
		}

		foreach ($results as $key => $produto) {
			
			$param = array(
				"id_author" => '8',
				"id_empresa" => $args['context'],
				"produto_name" => $produto->descricao,
				"produto_descricao" => $produto->descr_detalhada,
				"produto_codigo_cest" => $produto->codigo,
				"produto_codigo_omie" => $produto->codigo_produto,
				"produto_ean" => (isset($produto->ean) ? $produto->ean : ''),
				"produto_ncm" => (isset($produto->ncm) ? $produto->ncm : ''),
				"produto_origem" => 'omie',
				"produto_unidade" => (isset($produto->unidade) ? $produto->unidade : ''),
				"produto_valor" => (isset($produto->valor_unitario) ? $produto->valor_unitario : '')
			);

			if(!$this->check_isset_omie($param['produto_codigo_omie'])){

				$insert = $this->insert($param);

				if($insert['result']){
					$count_insert++;
				}else{
					$count_error++;
					$errors_data[] = array(
						'param' => $param,
						'response' => $insert
					);
				}

			}else{
				$count_repeats++;
				$errors_data[] = array(
					'param' => $param,
					'response' => "Item já existente"
				);
			}

		}

		$response['config'] = array(
			'total_data' => count($results),
			'count_insert' => $count_insert,
			'count_error' => $count_error,
			'errors_data' => $errors_data,
			'count_repeats' => $count_repeats,
			'produtos' => $results
		);

		return $response;



	}

	public function omieCurl($page = 1){

		$curl = \curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://app.omie.com.br/api/v1/geral/produtos/",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "{\"call\":\"ListarProdutos\",\"app_key\":\"7722651396\",\"app_secret\":\"dc643a3cf2fcde9c3b7c6e561bfb3b9f\",\"param\":[{\"pagina\":".$page.",\"registros_por_pagina\":100,\"apenas_importado_api\":\"N\",\"filtrar_apenas_omiepdv\":\"N\"}]}",
			CURLOPT_HTTPHEADER => array(
				"Cache-Control: no-cache",
				"Content-Type: application/json",
				"Postman-Token: b44fd0c8-b0cd-4611-9afa-8fb013c0b909"
		),
		));

		$response = json_decode(curl_exec($curl));
		$err = curl_error($curl);

		curl_close($curl);

		return $response;

	}

	public function check_isset_omie($code){

		$selectStatement = $this->db->select()->from('produtos')->where('produto_codigo_omie', '=', $code);
		$stmt = $selectStatement->execute();
		$data = $stmt->fetch();

		return (!$data ? false : true);

	}

}

?>