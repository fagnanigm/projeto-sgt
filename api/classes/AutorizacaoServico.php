<?php

namespace Classes;

class AutorizacaoServico {

	private $db;

	public $item_per_page = 5;

	public $code_length = 6;

	public $schema = array(
		"id_empresa",
		"id_author",

		# Cadastro
		"as_codigo",
		"as_codigo_seq",
		"as_revisao",
		"as_cliente_id",
		"as_cliente_fantasia",
		"as_cliente_responsavel",
		"as_cliente_email",
		"as_cliente_phone1",
		"as_cliente_phone2",
		"as_cliente_phone3",
		"as_cliente_ramal",
		"as_filial",
		"as_status",
		"as_data_cadastro",
		"as_vendedor",

		# Cotação
		"as_id_cotacao",
		"as_cotacao_nome",
		"as_cotacao_cod",

		# Projeto
		"as_id_projeto",
		"as_projeto_nome",
		"as_projeto_cod",
		"as_projeto_resumo",

		# Cliente tomador
		"as_cliente_tomador_type", // (consignatario, remetente, destinatario)

		# Cliente consignatario
		"as_id_cliente_consig",

		# Cliente remetente
		"as_id_cliente_remetente",

		# Cliente destinatario
		"as_id_cliente_destinatario",

		# Local de coleta
		"as_id_local_coleta",
		"as_local_coleta_exterior",
		"as_local_coleta_responsavel",

		# Local da entrega
		"as_id_local_entrega",
		"as_local_entrega_exterior",
		"as_local_entrega_responsavel",

		"as_local_observacao",
		"as_local_carregamento_date",
		"as_local_previsao_entrega",
		"as_local_equipamentos",
		"as_local_emitidos",

		# Faturamento
		"as_id_cliente_faturamento",
		"as_faturamento_forma",
		"as_faturamento_num_pedido",
		"as_faturamento_forma_pg",
		"as_faturamento_prazo",
		"as_faturamento_valor_total",
		"as_faturamento_observacao",
		"as_faturamento_date",
		"as_faturamento_date_envio",

		# Cobrança
		"as_id_cliente_cobranca",
		"as_financeiro_observacao",
		"as_faturamento_inclusos_preco",

		"as_cond_comerciais_adicionais",

		"active",
		"create_time"
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

		if(!isset($args['as_codigo'])){
			$response['error'] = 'O campo as_codigo é obrigatório.';
			return $response;
		}

		if(!isset($args['as_revisao'])){
			$response['error'] = 'O campo as_revisao é obrigatório.';
			return $response;
		}

		if(!isset($args['as_codigo_seq'])){
			$response['error'] = 'O campo as_codigo_seq é obrigatório.';
			return $response;
		}

		// Tratamento das datas
		if(isset($args['as_data_cadastro'])){
			$args['as_data_cadastro'] = abs($args['as_data_cadastro']);
			if($args['as_data_cadastro'] == 0){
				unset($args['as_data_cadastro']);
			}
		}

		if(isset($args['as_local_carregamento_date'])){
			$args['as_local_carregamento_date'] = abs($args['as_local_carregamento_date']);
			if($args['as_local_carregamento_date'] == 0){
				unset($args['as_local_carregamento_date']);
			}
		}

		if(isset($args['as_local_previsao_entrega'])){
			$args['as_local_previsao_entrega'] = abs($args['as_local_previsao_entrega']);
			if($args['as_local_previsao_entrega'] == 0){
				unset($args['as_local_previsao_entrega']);
			}
		}

		if(isset($args['as_faturamento_prazo'])){
			$args['as_faturamento_prazo'] = abs($args['as_faturamento_prazo']);
			if($args['as_faturamento_prazo'] == 0){
				unset($args['as_faturamento_prazo']);
			}
		}

		if(isset($args['as_faturamento_date'])){
			$args['as_faturamento_date'] = abs($args['as_faturamento_date']);
			if($args['as_faturamento_date'] == 0){
				unset($args['as_faturamento_date']);
			}
		}

		if(isset($args['as_faturamento_date_envio'])){
			$args['as_faturamento_date_envio'] = abs($args['as_faturamento_date_envio']);
			if($args['as_faturamento_date_envio'] == 0){
				unset($args['as_faturamento_date_envio']);
			}
		}

		

		

		// Init insert
		$data = array_flip($this->schema);

		// External params
		foreach ($data as $field => $value) {
			
			if(isset($args[$field])){

				$val = $args[$field];

				// Tratamento de CEP
				if($field == 'local_cep'){
					$val = Utilities::unMask($val);
				}

				// Tratamento de CEP
				if($field == 'as_codigo_seq'){
					$val = $this->code_parser_seq($val);
				}

				// Tratamento de id
				if(	$field == 'id_author' || 
					$field == 'as_cliente_id' || 
					$field == 'as_id_cotacao' ||
					$field == 'as_id_projeto' ||
					$field == 'as_id_cliente_consig' ||
					$field == 'as_id_cliente_remetente' ||
					$field == 'as_id_cliente_destinatario' ||
					$field == 'as_id_local_coleta' ||
					$field == 'as_id_local_entrega' ||
					$field == 'as_id_cliente_faturamento' ||
					$field == 'as_id_cliente_cobranca'
				){
					$val = intval($val);
				}

				// Tratamento de data
				if(	$field == 'as_data_cadastro' || 
					$field == 'as_local_carregamento_date' ||
					$field == 'as_local_previsao_entrega' || 
					$field == 'as_faturamento_prazo' || 
					$field == 'as_faturamento_date' ||
					$field == 'as_faturamento_date_envio'
				){
					$date = new \DateTime();
					$date->setTimestamp($val);
					$val = $date->format("Y-m-d\TH:i:s");
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

		$insertStatement = $this->db->insert(array_keys($data))->into('autorizacao_servico')->values(array_values($data));

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
		$selectStatement = $this->db->select(array('COUNT(*) AS total'))->from('autorizacao_servico')->whereMany(array('active' => 'Y', 'id_empresa' => $args['context']),'=');
		$stmt = $selectStatement->execute();
		$total_data = $stmt->fetch();

		if($args['getall'] == '1'){

			$config = array(
				'total' => $total_data['total'],
			);

			$response['config'] = $config;

			$select = $this->db->query('SELECT * FROM autorizacao_servico WHERE id_empresa = \''.$args['context'].'\' AND active = \'Y\' ORDER BY create_time');
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
				$select = $this->db->query('SELECT * FROM autorizacao_servico WHERE id_empresa = \''.$args['context'].'\' AND active = \'Y\' ORDER BY create_time OFFSET '.$offset.' ROWS FETCH NEXT '.$config['item_per_page'].' ROWS ONLY');
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
			$result = $this->apply_filter_local($result);
		}else{
			if($fetch == 'all'){
				foreach ($result as $key => $value) {
					$result[$key] = $this->apply_filter_local($value);
				}
			}
		}
		return $result;
	}

	public function apply_filter_local($local){

		foreach ($local as $key => $field) {
			$local[$key] = trim($field);
		}

		if(strlen($local['local_cnpj']) == 14){
			$local['local_cnpj'] = Utilities::mask($local['local_cnpj'],'##.###.###/####-##');
		}

		$create_time = new \DateTime($local['create_time']);
		$local['create_timestamp'] = $create_time->getTimestamp();
		

		return $local;
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

		$selectStatement = $this->db->select()->from('locais')->whereMany(array('id' => $id, 'active' => 'Y', 'id_empresa' => $args['context'] ), '=');

		$stmt = $selectStatement->execute();
		$data = $stmt->fetch();

		if($data){
			$response['local'] = $this->parser_fecth($data);
			$response['result'] = true;
		}else{
			$response['error'] = 'Nenhum local encontrada para essa ID.';
		}

		return $response;

	}

	public function code_parser_seq($val = 0){
		return str_pad($val, $this->code_length, "0", STR_PAD_LEFT); 
	}

	public function getnextcode($args){

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

		$select = $this->db->query('SELECT max(as_codigo_seq) AS code FROM autorizacao_servico WHERE id_empresa = \''.$args['context'].'\' AND active = \'Y\'');

		$max = $select->fetch(\PDO::FETCH_ASSOC);

		$max = ($max ? $max['code'] + 1 : 1);

		$response['code'] = $this->code_parser_seq($max);

		return $response;

	}

}

?>