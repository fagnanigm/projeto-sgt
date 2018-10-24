<?php

namespace Classes;

class Cotacoes {

	private $db;

	public $item_per_page = 5;

	public $schema = array(
		"id_revisao",
		"id_author",
		"id_empresa",
		"id_cliente",
		"id_vendedor",
		"id_forma_pagamento",
		"id_categoria",
		"cotacao_code_sequencial",
		"cotacao_code",
		"cotacao_revisao",
		"cotacao_cliente_nome",
		"cotacao_contato",
		"cotacao_email",
		"cotacao_phone_01",
		"cotacao_phone_02",
		"cotacao_phone_03",
		"cotacao_ramal",
		"cotacao_status",
		"cotacao_cadastro_data",
		"cotacao_previsao_mes",
		"cotacao_previsao_ano",
		"cotacao_projeto_nome",
		"cotacao_projeto_descricao",
		"cotacao_vi_imposto_icms",
		"cotacao_vi_imposto_inss",
		"cotacao_vi_imposto_ir",
		"cotacao_vi_taxas",
		"cotacao_vi_iss",
		"cotacao_vi_iss_percent",
		"cotacao_vi_escolta",
		"cotacao_vi_imposto_pis_cofins",
		"cotacao_vi_seguro",
		"cotacao_vi_pedagios",
		"cotacao_vi_seguro_percent",
		"cotacao_objeto_operacao",
		"cotacao_condicoes_pagamento_id",
		"cotacao_validade_proposta_id",
		"cotacao_prazo_razao_id",
		"cotacao_status_cadastro",
		"cotacao_carga_descarga",
		"cotacao_equipamentos",
		"cotacao_carencia",
		"cotacao_prazo_execucao",
		"cotacao_observacoes_finais",
		"cotacao_observacoes_internas",
		"cotacao_carga_descarga_carga",
		"cotacao_carga_descarga_carga_valor",
		"cotacao_carga_descarga_carga_equip",
		"cotacao_carga_descarga_descarga",
		"cotacao_carga_descarga_descarga_valor",
		"cotacao_carga_descarga_descarga_equip",
		"cotacao_carga_descarga_mob",
		"cotacao_carga_descarga_mob_valor",
		"cotacao_carga_descarga_mob_equip",
		"create_time",
		"active"
	);

	public $cotacao_status_array = array(
		'em-aberto' => 'Em aberto',
		'aprovado' => 'Aprovado',
		'aguardando-preco' => 'Aguardando preço',
		'cancelado' => 'Cancelado',
		'reprovado' => 'Reprovado',
		'orientativo' => 'Orientativo',
		'pendente' => 'Pendente'
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

		if(!isset($args['id_forma_pagamento'])){
			$response['error'] = 'O campo id_forma_pagamento é obrigatório.';
			return $response;
		}

		if(!isset($args['id_categoria'])){
			$response['error'] = 'O campo id_categoria é obrigatório.';
			return $response;
		}

		if(!isset($args['cotacao_code'])){
			$response['error'] = 'O campo cotacao_code é obrigatório.';
			return $response;
		}

		if(!isset($args['cotacao_code_sequencial'])){
			$response['error'] = 'O campo cotacao_code_sequencial é obrigatório.';
			return $response;
		}

		if(!isset($args['cotacao_cadastro_data'])){
			$response['error'] = 'O campo cotacao_cadastro_data é obrigatório.';
			return $response;
		}

		if(!isset($args['cotacao_projeto_nome'])){
			$response['error'] = 'O campo cotacao_projeto_nome é obrigatório.';
			return $response;
		}

		if(!isset($args['cotacao_vi_imposto_icms'])){
			$args['cotacao_vi_imposto_icms'] = false;
		}

		if(!isset($args['cotacao_vi_imposto_inss'])){
			$args['cotacao_vi_imposto_inss'] = false;
		}

		if(!isset($args['cotacao_vi_imposto_ir'])){
			$args['cotacao_vi_imposto_ir'] = false;
		}

		if(!isset($args['cotacao_vi_taxas'])){
			$args['cotacao_vi_taxas'] = false;
		}

		if(!isset($args['cotacao_vi_iss'])){
			$args['cotacao_vi_iss'] = false;
		}

		if(!isset($args['cotacao_vi_escolta'])){
			$args['cotacao_vi_escolta'] = false;
		}

		if(!isset($args['cotacao_vi_pedagios'])){
			$args['cotacao_vi_pedagios'] = false;
		}

		if(!isset($args['cotacao_vi_imposto_pis_cofins'])){
			$args['cotacao_vi_imposto_pis_cofins'] = false;
		}

		if(!isset($args['cotacao_vi_seguro'])){
			$args['cotacao_vi_seguro'] = false;
		}

		if(!isset($args['cotacao_carga_descarga_carga'])){
			$args['cotacao_carga_descarga_carga'] = false;
		}

		if(!isset($args['cotacao_carga_descarga_descarga'])){
			$args['cotacao_carga_descarga_descarga'] = false;
		}

		if(!isset($args['cotacao_carga_descarga_mob'])){
			$args['cotacao_carga_descarga_mob'] = false;
		}

		// Init insert
		$data = array_flip($this->schema);

		// External params
		foreach ($data as $field => $value) {
			
			if(isset($args[$field])){

				$val = $args[$field];

				
				// Tratamento de id
				if(
					$field == 'id_revisao' || 
					$field == 'id_author' || 
					$field == 'id_empresa' || 
					$field == 'id_cliente' || 
					$field == 'id_vendedor' || 
					$field == 'id_forma_pagamento' || 
					$field == 'id_categoria'
				){
					$val = intval($val);
				}

				if($field == 'cotacao_cadastro_data'){
					$date = new \DateTime();
					$date->setTimestamp($val);
					$val = $date->format("Y-m-d\TH:i:s");
				}

				// Checkboxes
				if(
					$field == 'cotacao_vi_imposto_icms' || 
					$field == 'cotacao_vi_imposto_inss' || 
					$field == 'cotacao_vi_imposto_ir' || 
					$field == 'cotacao_vi_taxas' || 
					$field == 'cotacao_vi_iss' || 
					$field == 'cotacao_vi_escolta' || 
					$field == 'cotacao_vi_imposto_pis_cofins' || 
					$field == 'cotacao_vi_seguro' || 
					$field == 'cotacao_vi_pedagios' || 
					$field == 'cotacao_carga_descarga_carga' || 
					$field == 'cotacao_carga_descarga_descarga' || 
					$field == 'cotacao_carga_descarga_mob'
				){
					$val = ($val ? 'Y' : 'N');
				}


				$data[$field] = $val;

			}else{
				unset($data[$field]);
			}

		}

		$data['cotacao_code_sequencial'] = $this->get_sequencial_from_code($data['cotacao_code']);

		if(!$data['cotacao_code_sequencial']){
			$response['error'] = 'Formato do código de cotação incorreto';
			return $response;
		}

		// Fixed params
		$date = new \DateTime();
		$data['create_time'] = $date->format("Y-m-d\TH:i:s");

		$data['active'] = 'Y';

		$response['data'] = $data;

		$insertStatement = $this->db->insert(array_keys($data))->into('cotacoes')->values(array_values($data));

		$response['id'] = $insertStatement->execute();

		if(strlen($response['id']) > 0){
			$response['result'] = true;

			// Atualiza ID da revisão da cotação principal se 
			if($args['id_revisao'] == '0'){

				$updateStatement = $this->db->query("UPDATE cotacoes SET id_revisao = '".$response['id']."' WHERE id = '".$response['id']."';");
				$updateStatement->execute();

			}

			// Insere objetos
			if(is_array($args['cotacao_caracteristica_objetos'])){

				$cotacaoObjetos = new CotacoesObjetos($this->db);

				foreach ($args['cotacao_caracteristica_objetos'] as $chave => $objeto) {

					$objeto['id_cotacao'] = $response['id'];
					
					$obj_response = $cotacaoObjetos->insert($objeto);

					if(!$obj_response['result']){
						$response['result'] = false;
						$response['error'] = $obj_response['error'];
						return $response;
					}

				}

			}

			// Salva anexos
			if(is_array($args['cotacao_anexos_objetos'])){

				foreach ($args['cotacao_anexos_objetos'] as $key => $anexo) {

					$data = array(
						'id_cotacao' => $response['id'],
						'id_arquivo' => $anexo['id'],
						'create_time' => $date->format("Y-m-d\TH:i:s")
					);
					
					$insertStatement = $this->db->insert(array_keys($data))->into('cotacoes_arquivos')->values(array_values($data));
					$insertStatement->execute();

				}

			}

			// Salva equipamentos / tipos comerciais
			if(is_array($args['cotacao_equipamentos_tipos'])){

				foreach ($args['cotacao_equipamentos_tipos'] as $ce_key => $ce_val) {

					$data = array(
						'id_cotacao' => $response['id'],
						'ce_descricao' => $ce_val['ce_descricao'],
						'ce_tipo_comercial_id' => $ce_val['ce_tipo_comercial_id'],
						'create_time' => $date->format("Y-m-d\TH:i:s"),
						'active' => 'Y'
					);
					
					$insertStatement = $this->db->insert(array_keys($data))->into('cotacoes_equipamentos')->values(array_values($data));
					$insertStatement->execute();

				}

			}


		}

		return $response;

	}


	public function get($args = array()){

		$response = array();
		$is_search = false;

		// Count total
		$query_count = "
			SELECT COUNT(*) AS total
				FROM cotacoes cm 
					INNER JOIN (SELECT MAX(c.id) as id FROM cotacoes c GROUP BY c.id_revisao) c 
					ON c.id = cm.id 
				WHERE cm.active = 'Y' 
		";

		// Filtro
		if(isset($args['cotacao_num'])){
			if(strlen(trim($args['cotacao_num'])) > 0){
				$query_count .= "AND cm.cotacao_code_sequencial LIKE '%".$args['cotacao_num']."%' ";
				$is_search = true;
			}
		}

		if(isset($args['cotacao_cliente'])){
			if(strlen(trim($args['cotacao_cliente'])) > 0){
				$query_count .= "AND cm.cotacao_cliente_nome LIKE '%".$args['cotacao_cliente']."%' ";
				$is_search = true;
			}
		}

		if(isset($args['cotacao_projeto'])){
			if(strlen(trim($args['cotacao_projeto'])) > 0){
				$query_count .= "AND cm.cotacao_projeto_nome LIKE '%".$args['cotacao_projeto']."%' ";
				$is_search = true;
			}
		}

		if(isset($args['cotacao_status'])){
			if(strlen(trim($args['cotacao_status'])) > 0){
				$query_count .= "AND cm.cotacao_status = '".$args['cotacao_status']."' ";
				$is_search = true;
			}
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

			$query = "
				SELECT cm.*, v.vendedor_nome
					FROM cotacoes cm 
						INNER JOIN (SELECT MAX(c.id) as id FROM cotacoes c GROUP BY c.id_revisao) c 
						ON c.id = cm.id
						INNER JOIN vendedores v 
						ON v.id = cm.id_vendedor
					WHERE cm.active = 'Y' 
			";

			// Filtro
			if(isset($args['cotacao_num'])){
				if(strlen(trim($args['cotacao_num'])) > 0){
					$query .= "AND cm.cotacao_code_sequencial LIKE '%".$args['cotacao_num']."%' "; 
				}
			}

			if(isset($args['cotacao_cliente'])){
				if(strlen(trim($args['cotacao_cliente'])) > 0){
					$query .= "AND cm.cotacao_cliente_nome LIKE '%".$args['cotacao_cliente']."%' "; 
				}
			}

			if(isset($args['cotacao_projeto'])){
				if(strlen(trim($args['cotacao_projeto'])) > 0){
					$query .= "AND cm.cotacao_projeto_nome LIKE '%".$args['cotacao_projeto']."%' "; 
				}
			}

			if(isset($args['cotacao_status'])){
				if(strlen(trim($args['cotacao_status'])) > 0){
					$query .= "AND cm.cotacao_status = '".$args['cotacao_status']."' "; 
				}
			}

			// Config
			$query .= "ORDER BY create_time DESC OFFSET ".$offset." ROWS FETCH NEXT ".$config['item_per_page']." ROWS ONLY";
			
			$select = $this->db->query($query);
			$response['results'] = $this->parser_fetch($select->fetchAll(\PDO::FETCH_ASSOC),'all');
			$response['config']['page_items_total'] = count($response['results']);

		}else{
			$response['results'] = [];
		}

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
		$cotacao['cotacao_vi_pedagios'] = ($cotacao['cotacao_vi_pedagios'] == 'Y' ? true : false);

		$cotacao['cotacao_carga_descarga_carga'] = ($cotacao['cotacao_carga_descarga_carga'] == 'Y' ? true : false);
		$cotacao['cotacao_carga_descarga_descarga'] = ($cotacao['cotacao_carga_descarga_descarga'] == 'Y' ? true : false);
		$cotacao['cotacao_carga_descarga_mob'] = ($cotacao['cotacao_carga_descarga_mob'] == 'Y' ? true : false);

		$cotacao['cotacao_status_text'] = $this->cotacao_status_array[$cotacao['cotacao_status']];

		// Puxa os objetos
		$cotacaoObjetos = new CotacoesObjetos($this->db);
		$cotacao_caracteristica_objetos = $cotacaoObjetos->get(array( 'id_cotacao' => $cotacao['id'] ));

		$cotacao['cotacao_caracteristica_objetos'] = $cotacao_caracteristica_objetos['results'];

		$select = $this->db->query("
			SELECT a.* FROM cotacoes_arquivos ca
				LEFT JOIN arquivos a 
				ON ca.id_arquivo = a.id
			WHERE ca.id_cotacao = '".$cotacao['id']."';
		");

		$cotacao_anexos_objetos = $select->fetchAll(\PDO::FETCH_ASSOC);

		if(is_array($cotacao_anexos_objetos)){
			foreach ($cotacao_anexos_objetos as $key => $value) {	
				$create_time = new \DateTime($value['create_time']);
				$cotacao_anexos_objetos[$key]['create_timestamp'] = $create_time->getTimestamp();
			}
		}else{
			$cotacao_anexos_objetos = array();
		}

		$cotacao['cotacao_anexos_objetos'] = $cotacao_anexos_objetos;


		// Puxa os equipamentos + tipos comerciais

		$select = $this->db->query("
			SELECT ce.* FROM cotacoes_equipamentos ce WHERE ce.id_cotacao = '".$cotacao['id']."';
		");

		$cotacao['cotacao_equipamentos_tipos'] = $select->fetchAll(\PDO::FETCH_ASSOC);
		
		return $cotacao;
	}

	public function delete($args = array()){

		$response = array(
			'result' => false
		);

		if(!isset($args['id'])){
			$response['error'] = 'ID da cotação não especificado';
			return $response;
		}

		$updateStatement = $this->db->update(array('active' => 'N'))->table('cotacoes')->where('id', '=', $args['id']);
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

					LEFT OUTER JOIN formas_pagamento fg 
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
			strtoupper($empresa['empresa_prefixo']) . '-' .
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

	public function cotacao_approve($args){

		$response = array(
			'result' => false
		);

		if(!isset($args['id'])){
			$response['error'] = 'O campo id é obrigatório.';
			return $response;
		}else{

			$cotacao = $this->get_by_id($args['id']);

			if(!$cotacao['result']){
				$response['error'] = 'Cotação não encontrada.';
				return $response;
			}else{
				$cotacao = $cotacao['cotacao'];
			}

		}

		$update_params = array(
			'cotacao_status' => 'aprovado'
		);

		$updateStatement = $this->db->update()->set($update_params)->table('cotacoes')->whereMany( array('id' => $args['id']), '=');
		$affectedRows = $updateStatement->execute();

		if($affectedRows > 0){

			$date = new \DateTime();

			$projeto = new Projetos($this->db);

			$response = $projeto->insert(array(		
				'id_cotacao' => $cotacao['id'],
				"id_author" => $cotacao['id_author'],
				"id_empresa" => $cotacao['id_empresa'],
				"id_cliente" => $cotacao['id_cliente'],
				"id_vendedor" => $cotacao['id_vendedor'],
				"id_forma_pagamento" => $cotacao['id_forma_pagamento'],
				"id_categoria" => $cotacao['id_categoria'],
				"projeto_code_sequencial" => $cotacao['cotacao_code_sequencial'],
				"projeto_code" => $cotacao['cotacao_code'],
				"projeto_revisao" => $cotacao['cotacao_revisao'],
				"projeto_cliente_nome" => $cotacao['cotacao_cliente_nome'],
				"projeto_contato" => $cotacao['cotacao_contato'],
				"projeto_email" => $cotacao['cotacao_email'],
				"projeto_phone_01" => $cotacao['cotacao_phone_01'],
				"projeto_phone_02" => $cotacao['cotacao_phone_02'],
				"projeto_phone_03" => $cotacao['cotacao_phone_03'],
				"projeto_ramal" => $cotacao['cotacao_ramal'],
				"projeto_status" => 'aprovado',
				"projeto_cadastro_data" => $date->format("Y-m-d\TH:i:s"),
				"projeto_nome" => $cotacao['cotacao_projeto_nome'],
				"projeto_descricao" => $cotacao['cotacao_projeto_descricao']
			));

			return $response;

		}else{
			$response['error'] = 'Nenhum registro afetado.';
			return $response;
		}

		$response['cotacao'] = $cotacao;

		return $response;

	}

}

?>