<?php

namespace Classes;

class Clientes {

	private $db;

	public $schema = array( 
		"id_author",
		"id_empresa",
		"cliente_person",
		"cliente_nome",
		"cliente_razao_social",
		"cliente_email",
		"cliente_cnpj",
		"cliente_cpf",
		"cliente_codigo_omie",
		"cliente_phone_01",
		"cliente_phone_01_ddd",
		"cliente_cnae" ,
		"cliente_ie" ,
		"cliente_im",
		"cliente_logradouro",
		"cliente_numero",
		"cliente_complemento",
		"cliente_bairro",
		"cliente_cidade",
		"cliente_cidade_ibge",
		"cliente_estado",
		"cliente_estado_ibge",
		"cliente_pais",
		"cliente_pais_ibge",
		"cliente_cep",
		"cliente_create_time",
		"cliente_origem",
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

		if(!isset($args['cliente_nome'])){
			$response['error'] = 'O campo cliente_nome é obrigatório.';
			return $response;
		}

		// Init insert

		$data = array_flip($this->schema);

		// External params
		foreach ($data as $field => $value) {
			
			if(isset($args[$field])){

				$val = $args[$field];

				// Tratamento de CPF
				if($field == 'cliente_cpf'){
					$val = Utilities::unMask($val);
				}

				// Tratamento de CNPJ
				if($field == 'cliente_cnpj'){
					$val = Utilities::unMask($val);
				}

				$data[$field] = $val;

			}else{
				unset($data[$field]);
			}

		}

		// Fixed params
		$date = new \DateTime();
		$data['cliente_create_time'] = $date->format("Y-m-d\TH:i:s");

		$data['active'] = 'Y';

		$response['data'] = $data;

		$insertStatement = $this->db->insert(array_keys($data))->into('clientes')->values(array_values($data));
		$response['id'] = $insertStatement->execute();

		if(strlen($response['id']) > 0){
			$response['result'] = true;
		}

		return $response;

	}

	public function get($args = array()){

		$response = array();
		$is_search = false;

		// Count total
		$query_count = "SELECT COUNT(*) AS total FROM clientes WHERE active = 'Y'";

		// Filtro
		if(isset($args['cliente_term'])){
			$query_count .= " AND (";
				$query_count .= "cliente_nome LIKE '%".$args['cliente_term']."%' OR ";
				$query_count .= "cliente_razao_social LIKE '%".$args['cliente_term']."%' OR ";
				$query_count .= "cliente_email LIKE '%".$args['cliente_term']."%' OR ";
				$query_count .= "cliente_cnpj LIKE '%".Utilities::unMask($args['cliente_term'])."%' ";
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

			$query = "SELECT * FROM clientes WHERE active = 'Y' ";

			// Filtro
			if(isset($args['cliente_term'])){
				$query .= " AND (";
					$query .= "cliente_nome LIKE '%".$args['cliente_term']."%' OR ";
					$query .= "cliente_razao_social LIKE '%".$args['cliente_term']."%' OR ";
					$query .= "cliente_email LIKE '%".$args['cliente_term']."%' OR ";
					$query .= "cliente_cnpj LIKE '%".Utilities::unMask($args['cliente_term'])."%' ";
				$query .= ") ";
			}

			// Config
			$query .= "ORDER BY cliente_create_time OFFSET ".$offset." ROWS FETCH NEXT ".$config['item_per_page']." ROWS ONLY";

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
			$result = $this->apply_filter_cliente($result);
		}else{
			if($fetch == 'all'){
				foreach ($result as $key => $value) {
					$result[$key] = $this->apply_filter_cliente($value);
				}
			}
		}
		return $result;
	}

	public function apply_filter_cliente($cliente){
		
		foreach ($cliente as $key => $field) {
			$cliente[$key] = trim($field);
		}
		if($cliente['cliente_person'] == 'f'){
			$cliente['cliente_cpf'] = (strlen($cliente['cliente_cpf']) > 0 ? Utilities::mask($cliente['cliente_cpf'],'###.###.###-##') : '');
		}else{
			$cliente['cliente_cnpj'] = (strlen($cliente['cliente_cnpj']) > 0 ? Utilities::mask($cliente['cliente_cnpj'],'##.###.###/####-##') : '');
		}
		return $cliente;
	}

	public function delete($args = array()){

		$response = array(
			'result' => false
		);

		if(!isset($args['id'])){
			$response['error'] = 'ID do cliente não especificado';
			return $response;
		}

		$updateStatement = $this->db->update(array('active' => 'N'))->table('clientes')->where('id', '=', $args['id']);
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

		$selectStatement = $this->db->select()->from('clientes')->whereMany(array('id' => $id, 'active' => 'Y'), '=' );

		$stmt = $selectStatement->execute();
		$data = $stmt->fetch();

		if($data){
			$response['cliente'] = $this->parser_fecth($data);
			$response['result'] = true;
		}else{
			$response['error'] = 'Nenhum cliente encontrado para essa ID.';
		}

		return $response;

	}

	public function importOmie($args = false){

		$response = array(
			'result' => true
		);

		$start_curl = $this->omieCurl(1);

		$results = $start_curl->clientes_cadastro;

		$count_insert = 0;
		$count_error = 0;
		$errors_data = array();
		$count_repeats = 0;

		for ($page = 2; $page <= $start_curl->total_de_paginas; $page++) { 
			$other_pages = $this->omieCurl($page);
			$results = array_merge($results, $other_pages->clientes_cadastro);
		}

		foreach ($results as $key => $cliente) {

			// Verifica se existe a tag clientes
			if(!empty($cliente->tags)){

				$is_cliente = false;

				foreach ($cliente->tags as $tg_key => $tg_val) {
					if(trim($tg_val->tag) == 'Cliente'){
						$is_cliente = true;
						break;
					}
				}

				if($is_cliente){

					$cidade = '';

					if(isset($cliente->cidade)){
						$cidade_pieces = explode(" ", $cliente->cidade);
						$cidade = trim($cidade_pieces[0]);
					}

					$ddd = '';

					if(isset($cliente->telefone1_ddd)){
						
						$ddd = trim($cliente->telefone1_ddd);

						if(strlen($ddd) == 3){
							$ddd = substr($cliente->telefone1_ddd, 1, 3);
						}

						if(strlen($ddd) == 1){
							$ddd = '';
						}

					}

					$param = array(
						"id_author" => '8',
						"id_empresa" => '0',
						"cliente_person" => ($cliente->pessoa_fisica == 'N' ? 'j' : 'f'),
						"cliente_nome" => $cliente->nome_fantasia,
						"cliente_razao_social" => ($cliente->pessoa_fisica == 'N' ? $cliente->razao_social : ''),
						"cliente_email" => $cliente->email,
						"cliente_cnpj" => ($cliente->pessoa_fisica == 'N' ? $cliente->cnpj_cpf : ''),
						"cliente_cpf" => ($cliente->pessoa_fisica != 'N' ? $cliente->cnpj_cpf : ''),
						"cliente_codigo_omie" => $cliente->codigo_cliente_omie,
						"cliente_phone_01" => (isset($cliente->telefone1_numero) ? Utilities::unMask($cliente->telefone1_numero) : '' ),
						"cliente_phone_01_ddd" => $ddd,
						"cliente_cnae" => (isset($cliente->cnae) ? $cliente->cnae : '' ),
						"cliente_ie" => (isset($cliente->inscricao_estadual) ? $cliente->inscricao_estadual : '' ),
						"cliente_im" => (isset($cliente->inscricao_municipal) ? $cliente->inscricao_municipal : '' ),
						"cliente_logradouro" => (isset($cliente->endereco) ? $cliente->endereco : '' ),
						"cliente_numero" => (isset($cliente->endereco_numero) ? $cliente->endereco_numero : '' ),
						"cliente_complemento" => (isset($cliente->complemento) ? $cliente->complemento : '' ),
						"cliente_bairro" => (isset($cliente->bairro) ? $cliente->bairro : '' ),
						"cliente_cidade" => $cidade,
						"cliente_cidade_ibge" => (isset($cliente->cidade_ibge) ? $cliente->cidade_ibge : '' ),
						"cliente_estado" => (isset($cliente->estado) ? $cliente->estado : '' ),
						"cliente_estado_ibge" => '',
						"cliente_pais" => 'Brasil',
						"cliente_pais_ibge" => (isset($cliente->codigo_pais) ? $cliente->codigo_pais : '' ),
						"cliente_cep" => (isset($cliente->cep) ? $cliente->cep : '' ),
						"cliente_origem" => 'omie'
					);

					if(!$this->check_isset_omie($param['cliente_codigo_omie'])){
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

			}

		}

		$response['config'] = array(
			'total_data' => count($results),
			'count_insert' => $count_insert,
			'count_error' => $count_error,
			'errors_data' => $errors_data,
			'count_repeats' => $count_repeats,
			// 'clientes' => $results
		);

		return $response;

	}

	public function omieCurl($page = 1){

		$curl = \curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://app.omie.com.br/api/v1/geral/clientes/",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "{\"call\":\"ListarClientes\",\"app_key\":\"3260290628\",\"app_secret\":\"b00c1acb1c6eca03142d020695c38e3b\",\"param\":[{\"pagina\":".$page.",\"registros_por_pagina\":300,\"apenas_importado_api\":\"N\"}]}",
			CURLOPT_HTTPHEADER => array(
				"Cache-Control: no-cache",
				"Content-Type: application/json"
			),
		));

		$response = json_decode(curl_exec($curl));
		$err = curl_error($curl);

		curl_close($curl);

		return $response;

	}

	public function check_isset_omie($code){

		$selectStatement = $this->db->select()->from('clientes')->where('cliente_codigo_omie', '=', $code);
		$stmt = $selectStatement->execute();
		$data = $stmt->fetch();

		return (!$data ? false : true);

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
		
		$select = $this->db->query('SELECT * FROM clientes 
			WHERE active = \'Y\' 
			AND (
				cliente_nome LIKE \'%'.$term.'%\' OR
				cliente_razao_social LIKE \'%'.$term.'%\' OR
				cliente_email LIKE \'%'.$term.'%\' OR
				cliente_cnpj LIKE \'%'.Utilities::unMask($term).'%\' OR
				cliente_cpf LIKE \'%'.Utilities::unMask($term).'%\'
			)
			ORDER BY cliente_nome');
			
		$response['results'] = $this->parser_fecth($select->fetchAll(\PDO::FETCH_ASSOC),'all');

		$response['result'] = true;

		return $response;

	}

}

?>