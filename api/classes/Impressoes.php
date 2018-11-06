<?php

namespace Classes;

class Impressoes {

	private $db;

	public $base = UPLOAD_PATH;
	public $impressoes_template_path = '../vendor/impressoes-templates/';

	function __construct($db = false){
		if(!$db){
			die();
		}
		$this->db = $db;
	}

	public function print_cotacao($args){

		$response = array(
			'result' => false
		);

		if(!isset($args['id_cotacao'])){
			$response['error'] = 'O campo id_cotacao é obrigatório.';
			return $response;
		}else{

			$cotacao_rc = new Cotacoes($this->db);

			$cotacao = $cotacao_rc->get_by_id($args['id_cotacao']);
			
			if(!$cotacao['result']){
				$response['error'] = 'Cotação não encontrada para essa ID';
				return $response;
			}

			$cotacao = $cotacao['cotacao'];

		}	

		// Gera o arquivo
		$mpdf = new \Mpdf\Mpdf([			
			'margin_top' => '28'
		]); 

		// Coleta o conteúdo

		$content = '';

		$condicoes_comerciais = '<tr>';
			$condicoes_comerciais .= '<td align="center">';
				$condicoes_comerciais .= ($cotacao['cotacao_vi_imposto_icms'] ? '<p>ICMS - INCLUSO</p>' : '');
				$condicoes_comerciais .= ($cotacao['cotacao_vi_imposto_inss'] ? '<p>INSS - INCLUSO</p>' : '');
				$condicoes_comerciais .= ($cotacao['cotacao_vi_imposto_ir'] ? '<p>IR - INCLUSO</p>' : '');
				$condicoes_comerciais .= ($cotacao['cotacao_vi_imposto_pis_cofins'] ? '<p>PIS COFINS - INCLUSO</p>' : '');
				$condicoes_comerciais .= ($cotacao['cotacao_vi_iss'] ? '<p>ISS - INCLUSO</p>' : '');
				$condicoes_comerciais .= ($cotacao['cotacao_vi_taxas'] ? '<p>Taxas/Licenças - INCLUSO</p>' : '');
				$condicoes_comerciais .= ($cotacao['cotacao_vi_escolta'] ? '<p>Escolta - INCLUSO</p>' : '');
				$condicoes_comerciais .= ($cotacao['cotacao_vi_pedagios'] ? '<p>Pedágios - INCLUSO</p>' : '');
			$condicoes_comerciais .= '</td>';
			$condicoes_comerciais .= '<td valign="top" align="center">';
				$condicoes_comerciais .= ($cotacao['cotacao_vi_seguro'] ? '<p>SEGURO - INCLUSO</p>' : 'Será de responsabilidade do cliente.');
			$condicoes_comerciais .= '</td>';
			$condicoes_comerciais .= '<td valign="top" align="center">'.$cotacao['prazo_nome'].' contados a partir '.strtolower($cotacao['razao_nome']).'. '.$cotacao['forma_nome'].'.</td>';
			$condicoes_comerciais .= '<td valign="top" align="center">'.$cotacao['validade_nome'].'</td>';
		$condicoes_comerciais .= '</tr>';

		if($cotacao['cotacao_modelo_impressao'] == 'modelo-1'){

			$objeto_operacao = '';

			foreach ($cotacao['cotacao_caracteristica_objetos'] as $key => $value) {
				$objeto_operacao .= '<tr>';
					$objeto_operacao .= '<td align="center">2.'.($key + 1).'</td>';
					$objeto_operacao .= '<td>'.$value['objeto_descricao'].'</td>';
					$objeto_operacao .= '<td align="center">'.$value['objeto_local_servico_cidade'].'/'.$value['objeto_local_servico_uf'].'</td>';
					$objeto_operacao .= '<td align="center">R$'.number_format($value['objeto_valor_total'],2,',','.').'</td>';
					$objeto_operacao .= '<td></td>';
				$objeto_operacao .= '</tr>';
			}

			$content = Utilities::file_reader($this->impressoes_template_path.'cotacoes-modelo-1.html',array(
				'|*ESCOPO_OPERACAO*|' => $cotacao['cotacao_objeto_operacao'],
				'|*COTACAO_CONTATO_NOME*|' => $cotacao['cotacao_contato'],
				'|*COTACAO_CONTATO_CELULAR*|' => $cotacao['cotacao_phone_01'],
				'|*COTACAO_CONTATO_EMAIL*|' => $cotacao['cotacao_email'],
				'|*CLIENTE_NOME*|' => $cotacao['cotacao_cliente_nome'],
				'|*PROJETO_NOME*|' => $cotacao['cotacao_projeto_nome'],
				'|*OBJETO_OPERACAO*|' => $objeto_operacao,
				'|*CONDICOES_COMERCIAIS*|' => $condicoes_comerciais
			));
		}else{

			$objeto_operacao = '';

			foreach ($cotacao['cotacao_caracteristica_objetos'] as $key => $value) {
				$objeto_operacao .= '<tr>';
					$objeto_operacao .= '<td align="center">2.'.($key + 1).'</td>';
					$objeto_operacao .= '<td align="center">'.$value['objeto_quantidade'].'</td>';
					$objeto_operacao .= '<td>'.$value['objeto_descricao'].'</td>';

					$objeto_operacao .= '<td align="center">'.$value['objeto_comprimento'].'</td>';
					$objeto_operacao .= '<td align="center">'.$value['objeto_largura'].'</td>';
					$objeto_operacao .= '<td align="center">'.$value['objeto_altura'].'</td>';
					$objeto_operacao .= '<td align="center">'.$value['objeto_peso_unit'].'</td>';

					$objeto_operacao .= '<td align="center">'.$value['objeto_origem_cidade'].'/'.$value['objeto_origem_uf'].'</td>';
					$objeto_operacao .= '<td align="center">'.$value['objeto_destino_cidade'].'/'.$value['objeto_destino_uf'].'</td>';
					$objeto_operacao .= '<td align="center">R$'.number_format($value['objeto_valor_unit'],2,',','.').'</td>';
				$objeto_operacao .= '</tr>';
			}

			$equipamentos_previstos = '';

			foreach ($cotacao['cotacao_equipamentos_tipos'] as $key => $value) {
				
				$equipamentos_previstos .= '<tr>';
					$equipamentos_previstos .= '<td style="padding: 0px 0px 0px 20px;">';
						$equipamentos_previstos .= '<strong>5.1 - '.$value['tipo_nome'].'</strong> - '.$value['tipo_descricao'];
					$equipamentos_previstos .='</td>';
				$equipamentos_previstos .= '</tr>';

			}

			$content = Utilities::file_reader($this->impressoes_template_path.'cotacoes-modelo-2.html',array(
				'|*ESCOPO_OPERACAO*|' => $cotacao['cotacao_objeto_operacao'],
				'|*COTACAO_CONTATO_NOME*|' => $cotacao['cotacao_contato'],
				'|*COTACAO_CONTATO_CELULAR*|' => $cotacao['cotacao_phone_01'],
				'|*COTACAO_CONTATO_EMAIL*|' => $cotacao['cotacao_email'],
				'|*CLIENTE_NOME*|' => $cotacao['cotacao_cliente_nome'],
				'|*PROJETO_NOME*|' => $cotacao['cotacao_projeto_nome'],
				'|*OBJETO_OPERACAO*|' => $objeto_operacao,
				'|*CONDICOES_COMERCIAIS*|' => $condicoes_comerciais,
				'|*CARGA_DESCARGA*|' => $cotacao['cotacao_carga_descarga'],
				'|*CARENCIA_ESTADIA*|' => $cotacao['cotacao_carencia'],
				'|*PRAZO_EXECUCAO*|' => $cotacao['cotacao_prazo_execucao'],
				'|*CONSIDERACOES_GERAIS*|' => $cotacao['cotacao_observacoes_finais'],
				'|*EQUIPAMENTOS_PREVISTOS*|' => $equipamentos_previstos
			));

		}

		$mpdf->SetFooter("Página {PAGENO} de {nb}");

		$mpdf->SetHTMLHeader('
			<table cellpadding="0" cellspacing="0" width="100%" style="width: 100%; border-bottom:1px solid #000;">
				<tr>
					<td><img src="logo.png"></td>
					<td width="220">
						<center>
							<table style="width: 220px;" width="220">
								<tr>
									<td style="border:1px solid #000;  font-size: 12px; padding: 4px 5px;">
										<strong>'.$cotacao['cotacao_code'].'</strong>
									</td>
								</tr>
							</table>
						</center>
					</td>
				</tr>
			</table>
		');

		$mpdf->WriteHTML($content);

		$mpdf->shrink_tables_to_fit = 1;

		// Salva o arquivo
		$dir = $this->get_directory();

		if($dir['result']){

			$filename = 'cotacao-'.date('d-m-Y-H-i-s').'.pdf';
			$dir_file = $dir['path'].$filename;

			$mpdf->Output($dir_file);

			$response['file'] = $dir['uri'].$filename;
			$response['result'] = true;

		}else{
			$response['error'] = $dir['error'];
		}		

		return $response;


	}

	public function print_as($args, $user = false){

		$response = array(
			'result' => false
		);

		if(!isset($args['id_as'])){
			$response['error'] = 'O campo id_as é obrigatório.';
			return $response;
		}else{

			$as_rc = new AutorizacaoServico($this->db);

			$as = $as_rc->get_by_id($args['id_as']);
			
			if(!$as['result']){
				$response['error'] = 'Autorização de serviço não encontrada para essa ID';
				return $response;
			}

			$as = $as['as'];

		}

		$response['as'] = $as;

		// Gera o arquivo
		$mpdf = new \Mpdf\Mpdf();

		// Coleta o conteúdo
		$clientes = new Clientes($this->db);
		$locais = new Locais($this->db);
		$vendedores = new Vendedores($this->db);

		// TOMADOR
		$cl_tomador = $clientes->get_by_id($as['id_cliente']);

		// COBRANÇA 
		$cl_cobranca = $clientes->get_by_id($as['as_as_id_cliente_faturamento']);

		// OPERACIONAL
		// CARGA
		$cl_op_carga = $clientes->get_by_id($as['as_op_id_cliente_remetente']);
		$cl_op_descarga = $clientes->get_by_id($as['as_op_id_cliente_destinatario']);
		$local_carga = $locais->get_by_id($as['as_op_id_local_coleta']);
		$local_descarga = $locais->get_by_id($as['as_op_id_local_entrega']);

		// VENDEDOR
		$vendedor = $vendedores->get_by_id($as['id_vendedor']);

		$objetos_operacao = "";

		foreach ($as['as_objetos_carregamento'] as $key => $value) {
			
			$objetos_operacao .= "<tr>";
				$objetos_operacao .= "<td>".$value['objeto_quantidade']."</td>";
				$objetos_operacao .= "<td>".$value['objeto_descricao']."</td>";
				$objetos_operacao .= "<td>".$value['objeto_origem'].' '.$value['objeto_destino']."</td>";
				$objetos_operacao .= "<td>".
					$value['objeto_comprimento'].' x '.
					$value['objeto_largura'].' x '.
					$value['objeto_altura'].' x '.
					$value['objeto_peso_unit'].' x '.
					"</td>";
				$objetos_operacao .= "<td>".$value['objeto_valor_mercadoria_total']."</td>";
				$objetos_operacao .= "<td>".$value['objeto_valor_total']."</td>";
			$objetos_operacao .= "</tr>";

		}

		// FATURAMENTO 
		$cl_faturamento = $clientes->get_by_id($as['id_cliente']);

		$content = Utilities::file_reader($this->impressoes_template_path.'autorizacao-servico.html',array(
			'|*NUMERO_AS*|' => $as['as_projeto_code'],

			// TOMADOR 
			'|*TOMADOR_CLIENTE*|' => $as['as_projeto_cliente_nome'],
			'|*TOMADOR_ENDERECO*|' => $cl_tomador['cliente']['cliente_logradouro'].' '.$cl_tomador['cliente']['cliente_numero'],
			'|*TOMADOR_BAIRRO*|' => $cl_tomador['cliente']['cliente_bairro'],
			'|*TOMADOR_CIDADE_UF*|' => $cl_tomador['cliente']['cliente_cidade'].' '.$cl_tomador['cliente']['cliente_estado'],
			'|*TOMADOR_CONTATO*|' => $as['as_projeto_contato'],
			'|*TOMADOR_EMAIL*|' => $as['as_projeto_email'],
			'|*TOMADOR_CNPJ*|' => $cl_tomador['cliente']['cliente_cnpj'],
			'|*TOMADOR_IE*|' => $cl_tomador['cliente']['cliente_ie'],
			'|*TOMADOR_COMPLEMENTO*|' => $cl_tomador['cliente']['cliente_complemento'],
			'|*TOMADOR_CEP*|' => $cl_tomador['cliente']['cliente_cep'],
			'|*TOMADOR_TEL_CEL*|' => $as['as_projeto_phone_03'].' '.$as['as_projeto_phone_01'],

			// COBRANÇA
			
			'|*COBRANCA_CLIENTE*|' => $cl_cobranca['cliente']['cliente_nome'],
			'|*COBRANCA_ENDERECO*|' => $cl_cobranca['cliente']['cliente_logradouro'].' '.$cl_cobranca['cliente']['cliente_numero'],
			'|*COBRANCA_BAIRRO*|' => $cl_cobranca['cliente']['cliente_bairro'],
			'|*COBRANCA_CIDADE_UF*|' => $cl_cobranca['cliente']['cliente_cidade'].' '.$cl_cobranca['cliente']['cliente_estado'],
			'|*COBRANCA_CONTATO*|' => $as['as_as_cliente_faturamento_contato_nome'],
			'|*COBRANCA_EMAIL*|' => $as['as_as_cliente_faturamento_contato_email'],
			'|*COBRANCA_CNPJ*|' => $cl_cobranca['cliente']['cliente_cnpj'],
			'|*COBRANCA_IE*|' => $cl_cobranca['cliente']['cliente_ie'],
			'|*COBRANCA_COMPLEMENTO*|' => $cl_cobranca['cliente']['cliente_complemento'],
			'|*COBRANCA_CEP*|' => $cl_cobranca['cliente']['cliente_cep'],
			'|*COBRANCA_TEL_CEL*|' => $as['as_as_cliente_faturamento_contato_telefone'].' '.$as['as_as_cliente_faturamento_contato_celular'],

			// OPERACIONAL
			'|*OPERACIONAL_CLIENTE_CARGA*|' => $cl_op_carga['cliente']['cliente_nome'],
			'|*OPERACIONAL_CARGA_ENDERECO*|' => $local_carga['local']['local_logradouro'].' '.$local_carga['local']['local_numero'],
			'|*OPERACIONAL_CARGA_BAIRRO*|' => $local_carga['local']['local_bairro'],
			'|*OPERACIONAL_CARGA_CIDADE_UF*|' => $local_carga['local']['local_cidade'].' '.$local_carga['local']['local_estado'],
			'|*OPERACIONAL_CARGA_CONTATO*|' => $as['as_op_local_coleta_contato'],
			'|*OPERACIONAL_CARGA_TEL_CEL*|' => $as['as_op_local_coleta_contato_telefone'].' '.$as['as_op_local_coleta_contato_celular'],
			'|*OPERACIONAL_CLIENTE_DESCARGA*|' => $cl_op_descarga['cliente']['cliente_nome'],
			'|*OPERACIONAL_DESCARGA_ENDERECO*|' => $local_descarga['local']['local_logradouro'].' '.$local_descarga['local']['local_numero'],
			'|*OPERACIONAL_DESCARGA_BAIRRO*|' => $local_descarga['local']['local_bairro'],
			'|*OPERACIONAL_DESCARGA_CIDADE_UF*|' => $local_descarga['local']['local_cidade'].' '.$local_descarga['local']['local_estado'],
			'|*OPERACIONAL_DESCARGA_CONTATO*|' => $as['as_op_local_entrega_contato'],
			'|*OPERACIONAL_DESCARGA_TEL_CEL*|' => $as['as_op_local_entrega_contato_telefone'].' '.$as['as_op_local_entrega_contato_celular'],
			'|*OPERACIONAL_OBS*|' => $as['as_op_observacao'],

			// OBJETO DA OPERAÇÃO
			'|*OBJETO_OPERACAO*|' => $objetos_operacao,

			// EQUIPAMENTOS
			'|*DATA_CARREGAMENTO*|' => date('d/m/Y',$as['as_op_data_carregamento']),
			'|*HORA_CARREGAMENTO*|' => date('H:i',$as['as_op_data_carregamento']),

			// FATURAMENTO
			'|*FATURAMENTO_CLIENTE*|' => $cl_faturamento['cliente']['cliente_nome'],
			'|*FATURAMENTO_ENDERECO*|' => $cl_faturamento['cliente']['cliente_logradouro'].' '.$cl_faturamento['cliente']['cliente_numero'],
			'|*FATURAMENTO_BAIRRO*|' => $cl_faturamento['cliente']['cliente_bairro'],
			'|*FATURAMENTO_CIDADE_UF*|' => $cl_faturamento['cliente']['cliente_cidade'].' '.$cl_faturamento['cliente']['cliente_estado'],
			'|*FATURAMENTO_CONTATO*|' => $as['as_fat_cliente_faturamento_contato_nome'],
			'|*FATURAMENTO_TEL_CEL*|' => $as['as_fat_cliente_faturamento_contato_telefone'].' '.$as['as_fat_cliente_faturamento_contato_celular'],
			'|*FATURAMENTO_OBS*|' => $as['as_fat_obs_faturamento'],

			// CONDIÇÕES COMERCIAIS ADICIONAIS
			'|*CONDICOES_COMERCIAIS_ADICIONAIS*|' => $as['as_as_condicoes_comerciais'],

			// OBSERVAÇÕES
			'|*OBSERVACOES_TEXT*|' => $as['as_fat_obs_faturamento'],
			'|*VENDEDOR*|' => $vendedor['vendedor']['vendedor_nome'],
			'|*EMITIENTE*|' => $user['username'],
			'|*DATA_EMISSAO*|' => date('d/m/Y H:i')

		));

		$mpdf->WriteHTML($content);

		$mpdf->shrink_tables_to_fit = 1;

		// Salva o arquivo
		$dir = $this->get_directory();

		if($dir['result']){

			$filename = 'AS-'.date('d-m-Y-H-i-s').'.pdf';
			$dir_file = $dir['path'].$filename;

			$mpdf->Output($dir_file);

			$response['file'] = $dir['uri'].$filename;
			$response['result'] = true;

		}else{
			$response['error'] = $dir['error'];
		}		

		return $response;

	}

	public function print_os($args){

		$response = array(
			'result' => false
		);

		if(!isset($args['id_as'])){
			$response['error'] = 'O campo id_as é obrigatório.';
			return $response;
		}else{

			$as_rc = new AutorizacaoServico($this->db);

			$as = $as_rc->get_by_id($args['id_as']);
			
			if(!$as['result']){
				$response['error'] = 'Autorização de serviço não encontrada para essa ID';
				return $response;
			}

			$as = $as['as'];

		}	

		// Gera o arquivo
		$mpdf = new \Mpdf\Mpdf();

		// Coleta o conteúdo

		$content = Utilities::file_reader($this->impressoes_template_path.'ordem-de-servico.html',array(
			'|*CONTENT*|' => '<pre>'.print_r($as, true).'</pre>'
		));

		$mpdf->WriteHTML($content);

		$mpdf->shrink_tables_to_fit = 1;

		// Salva o arquivo
		$dir = $this->get_directory();

		if($dir['result']){

			$filename = 'OS-'.date('d-m-Y-H-i-s').'.pdf';
			$dir_file = $dir['path'].$filename;

			$mpdf->Output($dir_file);

			$response['file'] = $dir['uri'].$filename;
			$response['result'] = true;

		}else{
			$response['error'] = $dir['error'];
		}		

		return $response;

	}


	public function print_occ($args){

		$response = array(
			'result' => false
		);

		if(!isset($args['id_as'])){
			$response['error'] = 'O campo id_as é obrigatório.';
			return $response;
		}else{

			$as_rc = new AutorizacaoServico($this->db);

			$as = $as_rc->get_by_id($args['id_as']);
			
			if(!$as['result']){
				$response['error'] = 'Autorização de serviço não encontrada para essa ID';
				return $response;
			}

			$as = $as['as'];

		}	

		// Gera o arquivo
		$mpdf = new \Mpdf\Mpdf();

		// Coleta o conteúdo

		$content = Utilities::file_reader($this->impressoes_template_path.'ordem-de-carga-e-coleta.html',array(
			'|*CONTENT*|' => '<pre>'.print_r($as, true).'</pre>'
		));

		$mpdf->WriteHTML($content);

		$mpdf->shrink_tables_to_fit = 1;

		// Salva o arquivo
		$dir = $this->get_directory();

		if($dir['result']){

			$filename = 'OCC-'.date('d-m-Y-H-i-s').'.pdf';
			$dir_file = $dir['path'].$filename;

			$mpdf->Output($dir_file);

			$response['file'] = $dir['uri'].$filename;
			$response['result'] = true;

		}else{
			$response['error'] = $dir['error'];
		}		

		return $response;

	}

	public function print_nd($args){

		$response = array(
			'result' => false
		);

		if(!isset($args['id_as'])){
			$response['error'] = 'O campo id_as é obrigatório.';
			return $response;
		}else{

			$as_rc = new AutorizacaoServico($this->db);

			$as = $as_rc->get_by_id($args['id_as']);
			
			if(!$as['result']){
				$response['error'] = 'Autorização de serviço não encontrada para essa ID';
				return $response;
			}

			$as = $as['as'];

		}	

		// Gera o arquivo
		$mpdf = new \Mpdf\Mpdf();

		// Coleta o conteúdo

		$content = Utilities::file_reader($this->impressoes_template_path.'nota-de-debito.html',array(
			'|*CONTENT*|' => '<pre>'.print_r($as, true).'</pre>'
		));

		$mpdf->WriteHTML($content);

		$mpdf->shrink_tables_to_fit = 1;

		// Salva o arquivo
		$dir = $this->get_directory();

		if($dir['result']){

			$filename = 'OCC-'.date('d-m-Y-H-i-s').'.pdf';
			$dir_file = $dir['path'].$filename;

			$mpdf->Output($dir_file);

			$response['file'] = $dir['uri'].$filename;
			$response['result'] = true;

		}else{
			$response['error'] = $dir['error'];
		}		

		return $response;

	}

	public function print_fatura($args){

		$response = array(
			'result' => false
		);

		if(!isset($args['id_as'])){
			$response['error'] = 'O campo id_as é obrigatório.';
			return $response;
		}else{

			$as_rc = new AutorizacaoServico($this->db);

			$as = $as_rc->get_by_id($args['id_as']);
			
			if(!$as['result']){
				$response['error'] = 'Autorização de serviço não encontrada para essa ID';
				return $response;
			}

			$as = $as['as'];

		}	

		// Gera o arquivo
		$mpdf = new \Mpdf\Mpdf();

		// Coleta o conteúdo

		$content = Utilities::file_reader($this->impressoes_template_path.'fatura.html',array(
			'|*CONTENT*|' => '<pre>'.print_r($as, true).'</pre>'
		));

		$mpdf->WriteHTML($content);

		$mpdf->shrink_tables_to_fit = 1;

		// Salva o arquivo
		$dir = $this->get_directory();

		if($dir['result']){

			$filename = 'Fatura-'.date('d-m-Y-H-i-s').'.pdf';
			$dir_file = $dir['path'].$filename;

			$mpdf->Output($dir_file);

			$response['file'] = $dir['uri'].$filename;
			$response['result'] = true;

		}else{
			$response['error'] = $dir['error'];
		}		

		return $response;

	}

	public function print_recibo($args){

		$response = array(
			'result' => false
		);

		if(!isset($args['id_as'])){
			$response['error'] = 'O campo id_as é obrigatório.';
			return $response;
		}else{

			$as_rc = new AutorizacaoServico($this->db);

			$as = $as_rc->get_by_id($args['id_as']);
			
			if(!$as['result']){
				$response['error'] = 'Autorização de serviço não encontrada para essa ID';
				return $response;
			}

			$as = $as['as'];

		}	

		// Gera o arquivo
		$mpdf = new \Mpdf\Mpdf();

		// Coleta o conteúdo

		$content = Utilities::file_reader($this->impressoes_template_path.'recibo.html',array(
			'|*CONTENT*|' => '<pre>'.print_r($as, true).'</pre>'
		));

		$mpdf->WriteHTML($content);

		$mpdf->shrink_tables_to_fit = 1;

		// Salva o arquivo
		$dir = $this->get_directory();

		if($dir['result']){

			$filename = 'Recibo-'.date('d-m-Y-H-i-s').'.pdf';
			$dir_file = $dir['path'].$filename;

			$mpdf->Output($dir_file);

			$response['file'] = $dir['uri'].$filename;
			$response['result'] = true;

		}else{
			$response['error'] = $dir['error'];
		}		

		return $response;

	}

	public function get_directory(){

		$response = array(
			'result' => false
		);

		$uri = '/impressoes/'.date('Y').'/'.date('m').'/';

		$path = $this->base.$uri;

		$response['path'] = $path;
		$response['uri'] = $uri;

		if(!is_dir($path)){
			if(mkdir($path, 0777, true)){
				$response['result'] = true;
			}else{
				$response['error'] = 'Falha ao criar diretório.';
			}
		}else{
			$response['result'] = true;
			$response['obs'] = 'Diretório já existente.';
		}

		return $response;

	}

}

?>