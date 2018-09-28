<?php

namespace Classes;

class AutorizacaoServico {

	private $db;

	public $item_per_page = 5;

	public $code_length = 6;

	public $schema = array(
		"id_cotacao",
		"id_revisao",
		"id_projeto",
		"id_author",
		"id_empresa",
		"id_cliente",
		"id_vendedor",
		"id_categoria",
		"as_projeto_code_sequencial",
		"as_projeto_code",
		"as_projeto_revisao",
		"as_projeto_cliente_nome",
		"as_projeto_contato",
		"as_projeto_email",
		"as_projeto_phone_01",
		"as_projeto_phone_02",
		"as_projeto_phone_03",
		"as_projeto_ramal",
		"as_projeto_status",
		"as_projeto_cadastro_data",
		"as_projeto_nome",
		"as_projeto_descricao",
		"as_as_cliente_tomador",
		"as_as_id_cliente_faturamento",
		"as_as_cliente_faturamento_nome",
		"as_as_cliente_faturamento_contato_nome",
		"as_as_cliente_faturamento_contato_telefone",
		"as_as_cliente_faturamento_contato_celular",
		"as_as_cliente_faturamento_contato_email",
		"as_as_forma_faturamento",
		"as_as_forma_pagamento",
		"as_as_prazo_pagamento",
		"as_as_condicoes_comerciais",
		"as_as_valor_retido",
		"as_as_incluso_comercial_rcfdc",
		"as_as_incluso_comercial_rcfdc_valor",
		"as_as_incluso_comercial_rctrc",
		"as_as_incluso_comercial_rctrc_valor",
		"as_as_incluso_comercial_despacho",
		"as_as_incluso_comercial_despacho_valor",
		"as_as_incluso_comercial_carga",
		"as_as_incluso_comercial_carga_valor",
		"as_as_incluso_comercial_descarga",
		"as_as_incluso_comercial_descarga_valor",
		"as_as_incluso_comercial_estadia",
		"as_as_incluso_comercial_estadia_valor",
		"as_as_incluso_comercial_pernoite",
		"as_as_incluso_comercial_pernoite_valor",
		"as_as_incluso_comercial_armazenagem",
		"as_as_incluso_comercial_armazenagem_valor",
		"as_as_incluso_comercial_advalorem",
		"as_as_incluso_comercial_advalorem_percent",
		"as_as_incluso_comercial_advalorem_valor",
		"as_as_incluso_comercial_seguro",
		"as_as_incluso_comercial_seguro_percent",
		"as_as_incluso_comercial_seguro_valor",
		"as_as_incluso_contabil_icms",
		"as_as_incluso_contabil_icms_percent",
		"as_as_incluso_contabil_icms_valor",
		"as_as_incluso_contabil_iss",
		"as_as_incluso_contabil_iss_percent",
		"as_as_incluso_contabil_iss_valor",
		"as_as_incluso_contabil_inss",
		"as_as_incluso_contabil_inss_percent",
		"as_as_incluso_contabil_inss_valor",
		"as_as_incluso_contabil_ir",
		"as_as_incluso_contabil_ir_percent",
		"as_as_incluso_contabil_ir_valor",
		"as_as_incluso_contabil_pis",
		"as_as_incluso_contabil_pis_percent",
		"as_as_incluso_contabil_pis_valor",
		"as_as_incluso_contabil_cofins",
		"as_as_incluso_contabil_cofins_percent",
		"as_as_incluso_contabil_cofins_valor",
		"as_as_incluso_contabil_csll",
		"as_as_incluso_contabil_csll_percent",
		"as_as_incluso_contabil_csll_valor",
		"as_as_incluso_contabil_cp",
		"as_as_incluso_contabil_cp_percent",
		"as_as_incluso_contabil_cp_valor",
		"as_op_id_cliente_remetente",
		"as_op_cliente_remetente_nome",
		"as_op_cliente_remetente_contato_nome",
		"as_op_cliente_remetente_contato_telefone",
		"as_op_cliente_remetente_contato_celular",
		"as_op_cliente_remetente_contato_email",
		"as_op_id_cliente_destinatario",
		"as_op_cliente_destinatario_nome",
		"as_op_cliente_destinatario_contato_nome",
		"as_op_cliente_destinatario_contato_telefone",
		"as_op_cliente_destinatario_contato_celular",
		"as_op_cliente_destinatario_contato_email",
		"as_op_id_local_coleta",
		"as_op_local_coleta_nome",
		"as_op_local_coleta_contato",
		"as_op_id_local_entrega",
		"as_op_local_entrega_nome",
		"as_op_local_entrega_contato",
		"as_op_observacao",
		"as_op_data_carregamento",
		"as_op_data_previsao",
		"as_op_id_veiculo",
		"as_op_veiculo_frota",
		"as_op_equipamentos",
		"as_fat_id_cliente_faturamento",
		"as_fat_cliente_faturamento_nome",
		"as_fat_cliente_faturamento_contato_nome",
		"as_fat_cliente_faturamento_contato_telefone",
		"as_fat_cliente_faturamento_contato_celular",
		"as_fat_cliente_faturamento_contato_email",
		"as_fat_forma_faturamento",
		"as_fat_num_pedido_compra",
		"as_fat_forma_pagamento",
		"as_fat_prazo_pagamento",
		"as_fat_valor_total_as_bruto",
		"as_fat_valor_total_as_liquido",
		"as_fat_valor_resultado_bruto",
		"as_fat_obs_faturamento",
		"as_fat_data_faturamento",
		"as_fat_data_envio",
		"as_fat_id_cliente_cobranca",
		"as_fat_cliente_cobranca_nome",
		"as_fat_cliente_cobranca_contato_nome",
		"as_fat_cliente_cobranca_contato_telefone",
		"as_fat_cliente_cobranca_contato_celular",
		"as_fat_cliente_cobranca_contato_email",
		"as_fat_obs_financeiro",
		"create_time",
		"active"
	);

	public $status_array = array(
		'em-aberto' => 'Em aberto',
		'em-viagem' => 'Em viagem',
		'faturado' => 'Faturado'
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
			$response['error'] = 'O campo id_author é obrigatório.';
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

		if(!isset($args['id_revisao'])){
			$args['id_revisao'] = '0';
		}

		if(!isset($args['id_empresa'])){
			$response['error'] = 'O campo id_empresa é obrigatório.';
			return $response;
		}

		if(!isset($args['id_cliente'])){
			$response['error'] = 'O campo id_cliente é obrigatório.';
			return $response;
		}

		if(!isset($args['id_vendedor'])){
			$response['error'] = 'O campo id_vendedor é obrigatório.';
			return $response;
		}

		if(!isset($args['id_categoria'])){
			$response['error'] = 'O campo id_categoria é obrigatório.';
			return $response;
		}

		if(!isset($args['as_projeto_code'])){
			$response['error'] = 'O campo as_projeto_code é obrigatório.';
			return $response;
		}

		if(!isset($args['as_projeto_code_sequencial'])){
			$response['error'] = 'O campo as_projeto_code_sequencial é obrigatório.';
			return $response;
		}

		// Checkbox
		if(!isset($args['as_as_incluso_comercial_rcfdc']) || strlen($args['as_as_incluso_comercial_rcfdc']) == 0){
			$args['as_as_incluso_comercial_rcfdc'] = false;
		}

		if(!isset($args['as_as_incluso_comercial_rctrc']) || strlen($args['as_as_incluso_comercial_rctrc']) == 0){
			$args['as_as_incluso_comercial_rctrc'] = false;
		}

		if(!isset($args['as_as_incluso_comercial_despacho']) || strlen($args['as_as_incluso_comercial_despacho']) == 0){
			$args['as_as_incluso_comercial_despacho'] = false;
		}

		if(!isset($args['as_as_incluso_comercial_carga']) || strlen($args['as_as_incluso_comercial_carga']) == 0){
			$args['as_as_incluso_comercial_carga'] = false;
		}

		if(!isset($args['as_as_incluso_comercial_descarga']) || strlen($args['as_as_incluso_comercial_descarga']) == 0){
			$args['as_as_incluso_comercial_descarga'] = false;
		}

		if(!isset($args['as_as_incluso_comercial_estadia']) || strlen($args['as_as_incluso_comercial_estadia']) == 0){
			$args['as_as_incluso_comercial_estadia'] = false;
		}

		if(!isset($args['as_as_incluso_comercial_pernoite']) || strlen($args['as_as_incluso_comercial_pernoite']) == 0){
			$args['as_as_incluso_comercial_pernoite'] = false;
		}

		if(!isset($args['as_as_incluso_comercial_armazenagem']) || strlen($args['as_as_incluso_comercial_armazenagem']) == 0){
			$args['as_as_incluso_comercial_armazenagem'] = false;
		}

		if(!isset($args['as_as_incluso_comercial_advalorem']) || strlen($args['as_as_incluso_comercial_advalorem']) == 0){
			$args['as_as_incluso_comercial_advalorem'] = false;
		}

		if(!isset($args['as_as_incluso_comercial_seguro']) || strlen($args['as_as_incluso_comercial_seguro']) == 0){
			$args['as_as_incluso_comercial_seguro'] = false;
		}

		if(!isset($args['as_as_incluso_contabil_icms']) || strlen($args['as_as_incluso_contabil_icms']) == 0){
			$args['as_as_incluso_contabil_icms'] = false;
		}

		if(!isset($args['as_as_incluso_contabil_iss']) || strlen($args['as_as_incluso_contabil_iss']) == 0){
			$args['as_as_incluso_contabil_iss'] = false;
		}

		if(!isset($args['as_as_incluso_contabil_inss']) || strlen($args['as_as_incluso_contabil_inss']) == 0){
			$args['as_as_incluso_contabil_inss'] = false;
		}

		if(!isset($args['as_as_incluso_contabil_ir']) || strlen($args['as_as_incluso_contabil_ir']) == 0){
			$args['as_as_incluso_contabil_ir'] = false;
		}

		if(!isset($args['as_as_incluso_contabil_pis']) || strlen($args['as_as_incluso_contabil_pis']) == 0){
			$args['as_as_incluso_contabil_pis'] = false;
		}

		if(!isset($args['as_as_incluso_contabil_cofins']) || strlen($args['as_as_incluso_contabil_cofins']) == 0){
			$args['as_as_incluso_contabil_cofins'] = false;
		}

		if(!isset($args['as_as_incluso_contabil_csll']) || strlen($args['as_as_incluso_contabil_csll']) == 0){
			$args['as_as_incluso_contabil_csll'] = false;
		}

		if(!isset($args['as_as_incluso_contabil_cp']) || strlen($args['as_as_incluso_contabil_cp']) == 0){
			$args['as_as_incluso_contabil_cp'] = false;
		}

		// Init insert
		$data = array_flip($this->schema);

		// External params
		foreach ($data as $field => $value) {
			
			if(isset($args[$field])){

				if(is_bool($args[$field]) || strlen($args[$field]) > 0){

					$val = $args[$field];

					// Tratamento de id
					if(
						$field == 'id_revisao' || 
						$field == 'id_author' || 
						$field == 'id_empresa' || 
						$field == 'id_cliente' || 
						$field == 'id_vendedor' || 
						$field == 'id_categoria'
					){
						$val = intval($val);
					}

					
					// Tratamento de data
					if(	
						$field == 'as_projeto_cadastro_data' ||
						$field == 'as_as_prazo_pagamento'
					){
						$date = new \DateTime();
						$date->setTimestamp($val);
						$val = $date->format("Y-m-d\TH:i:s");
					}

					// Checkboxes
					if(
						$field == 'as_as_incluso_comercial_rcfdc' || 
						$field == 'as_as_incluso_comercial_rctrc' || 
						$field == 'as_as_incluso_comercial_despacho' || 
						$field == 'as_as_incluso_comercial_carga' || 
						$field == 'as_as_incluso_comercial_descarga' || 
						$field == 'as_as_incluso_comercial_estadia' || 
						$field == 'as_as_incluso_comercial_pernoite' || 
						$field == 'as_as_incluso_comercial_armazenagem' || 
						$field == 'as_as_incluso_comercial_advalorem' || 
						$field == 'as_as_incluso_comercial_seguro' || 
						$field == 'as_as_incluso_contabil_icms' || 
						$field == 'as_as_incluso_contabil_iss' || 
						$field == 'as_as_incluso_contabil_inss' || 
						$field == 'as_as_incluso_contabil_ir' || 
						$field == 'as_as_incluso_contabil_pis' || 
						$field == 'as_as_incluso_contabil_cofins' || 
						$field == 'as_as_incluso_contabil_csll' || 
						$field == 'as_as_incluso_contabil_cp'
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

		$data['as_projeto_code_sequencial'] = $this->get_sequencial_from_code($data['as_projeto_code']);

		if(!$data['as_projeto_code_sequencial']){
			$response['error'] = 'Formato do código de AS incorreto';
			return $response;
		}

		// Fixed params
		$date = new \DateTime();
		$data['create_time'] = $date->format("Y-m-d\TH:i:s");

		$data['active'] = 'Y';

		$response['data'] = $data;

		$insertStatement = $this->db->insert(array_keys($data))->into('autorizacao_servico')->values(array_values($data));

		$response['id'] = $insertStatement->execute();

		if(strlen($response['id']) > 0){
			$response['result'] = true;

			// Atualiza ID da revisão da cotação principal se 
			if($args['id_revisao'] == '0'){

				$updateStatement = $this->db->query("UPDATE autorizacao_servico SET id_revisao = '".$response['id']."' WHERE id = '".$response['id']."';");
				$updateStatement->execute();

			}

			// Insere objetos
			if(is_array($args['as_objetos_carregamento'])){

				$asObjetos = new AsObjetos($this->db);

				foreach ($args['as_objetos_carregamento'] as $chave => $objeto) {

					$objeto['id_as'] = $response['id'];
					
					$obj_response = $asObjetos->insert($objeto);

					if(!$obj_response['result']){
						$response['result'] = false;
						$response['error'] = $obj_response['error'];
						return $response;
					}

				}

			}


		}

		return $response;

	}


	public function get($args = array()){

		$response = array();
		$is_search = false;
		$paginate = (isset($args['paginate']) ? $args['paginate'] : true);

		// Count total
		$query_count = "
			SELECT COUNT(*) AS total
				FROM autorizacao_servico a1 
					INNER JOIN (SELECT MAX(a2.id) as id FROM autorizacao_servico a2 GROUP BY a2.id_revisao) a2 
					ON a2.id = a1.id 
				WHERE a1.active = 'Y' 
		";

		// Filter 
		if(isset($args['id_projeto'])){
			if(strlen(trim($args['id_projeto'])) > 0){
				$query_count .= " AND a1.id_projeto = '".$args['id_projeto']."' ";
				$is_search = true;
			}
		}

		if(isset($args['as_num'])){
			if(strlen(trim($args['as_num'])) > 0){
				$query_count .= " AND a1.as_projeto_code_sequencial LIKE '%".$args['as_num']."%' ";
				$is_search = true;
			}
		}

		if(isset($args['cliente_nome'])){
			if(strlen(trim($args['cliente_nome'])) > 0){
				$query_count .= " AND a1.as_projeto_cliente_nome LIKE '%".$args['cliente_nome']."%' ";
				$is_search = true;
			}
		}

		if(isset($args['projeto_nome'])){
			if(strlen(trim($args['projeto_nome'])) > 0){
				$query_count .= " AND a1.as_projeto_nome LIKE '%".$args['projeto_nome']."%' ";
				$is_search = true;
			}
		}

		if(isset($args['vendedor_id'])){
			if(strlen(trim($args['vendedor_id'])) > 0){
				$query_count .= " AND a1.id_vendedor = '".$args['vendedor_id']."' ";
				$is_search = true;
			}
		}
	

		$count = $this->db->query($query_count);
		$total_data = $count->fetch();

		$config = array(
			'total' => $total_data['total']
		);

		if($paginate){

			$config['item_per_page'] = $this->item_per_page;
			$config['total_pages'] = ceil($total_data['total'] / $this->item_per_page);
			$config['current_page'] = (isset($args['current_page']) ? $args['current_page'] : 1 );
			$config['is_search'] = $is_search;

		}

		$response['config'] = $config;

		if($config['current_page'] <= $config['total_pages']){

			// Offset
			$offset = ($config['current_page'] == '1' ? 0 : ($config['current_page'] - 1) * $config['item_per_page'] );

			$query = "
				SELECT a1.*, v.vendedor_nome, c.cat_name
					FROM autorizacao_servico a1 
						INNER JOIN (SELECT MAX(a2.id) as id FROM autorizacao_servico a2 GROUP BY a2.id_revisao) a2
						ON a2.id = a1.id
						INNER JOIN vendedores v 
						ON v.id = a1.id_vendedor
						LEFT JOIN categorias c 
						ON c.id = a1.id_categoria
					WHERE a1.active = 'Y' 
			";

			// Filter 
			if(isset($args['id_projeto'])){
				if(strlen(trim($args['id_projeto'])) > 0){
					$query .= " AND a1.id_projeto = '".$args['id_projeto']."' ";
				}
			}

			if(isset($args['as_num'])){
				if(strlen(trim($args['as_num'])) > 0){
					$query .= " AND a1.as_projeto_code_sequencial LIKE '%".$args['as_num']."%' ";
				}
			}

			if(isset($args['cliente_nome'])){
				if(strlen(trim($args['cliente_nome'])) > 0){
					$query .= " AND a1.as_projeto_cliente_nome LIKE '%".$args['cliente_nome']."%' ";
				}
			}

			if(isset($args['projeto_nome'])){
				if(strlen(trim($args['projeto_nome'])) > 0){
					$query .= " AND a1.as_projeto_nome LIKE '%".$args['projeto_nome']."%' ";
				}
			}	

			if(isset($args['vendedor_id'])){
				if(strlen(trim($args['vendedor_id'])) > 0){
					$query .= " AND a1.id_vendedor = '".$args['vendedor_id']."' ";
				}
			}	

			// Order by
			$query .= "ORDER BY a1.create_time DESC ";

			if($paginate){
				$query .= "OFFSET ".$offset." ROWS FETCH NEXT ".$config['item_per_page']." ROWS ONLY";
			}

			$select = $this->db->query($query);
			$response['results'] = $this->parser_fetch($select->fetchAll(\PDO::FETCH_ASSOC),'all');

			if($paginate){
				$response['config']['page_items_total'] = count($response['results']);
			}

		}else{
			$response['results'] = [];
		}


		return $response;

	}

	public function parser_fetch($result, $fetch = 'one'){
		if($fetch == 'one'){
			$result = $this->apply_filter_as($result);
		}else{
			if($fetch == 'all'){
				foreach ($result as $key => $value) {
					$result[$key] = $this->apply_filter_as($value);
				}
			}
		}
		return $result;
	}

	public function apply_filter_as($as){

		foreach ($as as $key => $field) {
			$as[$key] = trim($field);
		}

		$as['as_projeto_status_text'] = $this->status_array[$as['as_projeto_status']];

		$create_time = new \DateTime($as['create_time']);
		$as['create_timestamp'] = $create_time->getTimestamp();

		//  DATA: as_projeto_cadastro_data
		$as_projeto_cadastro_data = new \DateTime($as['as_projeto_cadastro_data']);
		$as['as_projeto_cadastro_data'] = $as_projeto_cadastro_data->getTimestamp();

		// DATA: as_as_prazo_pagamento
		$as_as_prazo_pagamento = new \DateTime($as['as_as_prazo_pagamento']);
		$as['as_as_prazo_pagamento'] = $as_as_prazo_pagamento->getTimestamp();

		// Checkbox
		$as['as_as_incluso_comercial_rcfdc'] = ($as['as_as_incluso_comercial_rcfdc'] == 'Y' ? true : false);
		$as['as_as_incluso_comercial_rctrc'] = ($as['as_as_incluso_comercial_rctrc'] == 'Y' ? true : false);
		$as['as_as_incluso_comercial_despacho'] = ($as['as_as_incluso_comercial_despacho'] == 'Y' ? true : false);
		$as['as_as_incluso_comercial_carga'] = ($as['as_as_incluso_comercial_carga'] == 'Y' ? true : false);
		$as['as_as_incluso_comercial_descarga'] = ($as['as_as_incluso_comercial_descarga'] == 'Y' ? true : false);
		$as['as_as_incluso_comercial_estadia'] = ($as['as_as_incluso_comercial_estadia'] == 'Y' ? true : false);
		$as['as_as_incluso_comercial_pernoite'] = ($as['as_as_incluso_comercial_pernoite'] == 'Y' ? true : false);
		$as['as_as_incluso_comercial_armazenagem'] = ($as['as_as_incluso_comercial_armazenagem'] == 'Y' ? true : false);
		$as['as_as_incluso_comercial_advalorem'] = ($as['as_as_incluso_comercial_advalorem'] == 'Y' ? true : false);
		$as['as_as_incluso_comercial_seguro'] = ($as['as_as_incluso_comercial_seguro'] == 'Y' ? true : false);
		$as['as_as_incluso_contabil_icms'] = ($as['as_as_incluso_contabil_icms'] == 'Y' ? true : false);
		$as['as_as_incluso_contabil_iss'] = ($as['as_as_incluso_contabil_iss'] == 'Y' ? true : false);
		$as['as_as_incluso_contabil_inss'] = ($as['as_as_incluso_contabil_inss'] == 'Y' ? true : false);
		$as['as_as_incluso_contabil_ir'] = ($as['as_as_incluso_contabil_ir'] == 'Y' ? true : false);
		$as['as_as_incluso_contabil_pis'] = ($as['as_as_incluso_contabil_pis'] == 'Y' ? true : false);
		$as['as_as_incluso_contabil_cofins'] = ($as['as_as_incluso_contabil_cofins'] == 'Y' ? true : false);
		$as['as_as_incluso_contabil_csll'] = ($as['as_as_incluso_contabil_csll'] == 'Y' ? true : false);
		$as['as_as_incluso_contabil_cp'] = ($as['as_as_incluso_contabil_cp'] == 'Y' ? true : false);

		// Puxa os objetos
		$asObjetos = new AsObjetos($this->db);
		$as_objetos_carregamento = $asObjetos->get(array( 'id_as' => $as['id'] ));
		$as['as_objetos_carregamento'] = $as_objetos_carregamento['results'];
				
		return $as;
	}

	public function delete($args = array()){

		$response = array(
			'result' => false
		);

		if(!isset($args['id'])){
			$response['error'] = 'ID do local não especificado';
			return $response;
		}

		$updateStatement = $this->db->update(array('active' => 'N'))->table('locais')->where('id', '=', $args['id']);
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

		unset($args['create_timestamp']);

		$updateStatement = $this->db->update()->set($args)->table('locais')->whereMany( array('id' => $id, 'id_empresa' => $context), '=');

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

		$selectStatement = $this->db->select()->from('autorizacao_servico')->whereMany(array('id' => $id, 'active' => 'Y'), '=');

		$stmt = $selectStatement->execute();
		$data = $stmt->fetch();

		if($data){
			$response['as'] = $this->parser_fetch($data);
			$response['result'] = true;
		}else{
			$response['error'] = 'Nenhuma AS encontrada para essa ID.';
		}

		return $response;

	}

	public function code_parser_seq($val = 0){
		return str_pad($val, $this->code_length, "0", STR_PAD_LEFT); 
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

		if(isset($args['as_projeto_code_sequencial'])){
			$sequencial = $args['as_projeto_code_sequencial'];
		}else{
			$select = $this->db->query('SELECT max(as_projeto_code_sequencial) AS code FROM autorizacao_servico;');
			$max_code_sequencial = $select->fetch(\PDO::FETCH_ASSOC);
			$sequencial = str_pad(($max_code_sequencial['code'] + 1), 6, '0', STR_PAD_LEFT);
		}

		$response['as_projeto_code'] = 
			strtoupper($empresa['empresa_prefixo']) . 
			'-AS-' .
			$sequencial .
			'-' .
			'rev.' . $args['revisao'];

		$response['as_projeto_code_sequencial'] = $sequencial;

		$response['result'] = true;

		return $response;

	}

	public function get_sequencial_from_code($code){

		$code = explode("-", $code);

		if(isset($code[2])){
			
			return $code[2];
		}else{
			return false;
		}

	}

	public function get_revisoes($id_as = false){

		$response = array(
			'result' => false
		);

		if(!$id_as){
			$response['error'] = 'ID da AS não informado';
			return $response;
		}else{

			$as = $this->get_by_id($id_as);

			if(!$as['result']){
				$response['error'] = $as['error'];
				return $response;
			}else{
				$as = $as['as'];
			}

		}

		$select = $this->db->query("SELECT * FROM autorizacao_servico WHERE id_revisao = '".$as['id_revisao']."' AND id != '".$as['id']."' AND active = 'Y' ORDER BY create_time");
		$revisoes = $this->parser_fetch($select->fetchAll(\PDO::FETCH_ASSOC),'all');

		// ------------------------------

		$response['revisoes'] = $revisoes;
		$response['as'] = $as;
		$response['result'] = true;

		return $response;

	}

}

?>