<?php

namespace Classes;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Relatorios {

	private $db;

	public $base = UPLOAD_PATH;
	public $relatorios_template_path = '../vendor/relatorios-templates/';

	function __construct($db = false){
		if(!$db){
			die();
		}
		$this->db = $db;
	}

	
	public function generate_relatorios_as($args){

		$response = array(
			'result' => false
		);

		// Recolhe as informações do arquivo

		$header = array(
			"Número da AS",
			"Status",
			"Cliente",
			"Nome do projeto",
			"Valor",
			"Custo",
			"Resultado",
			"Percentual",
			"Vendedor",
			"Categoria",
			"Criação"
		);

		$query = "
			SELECT 
				a1.as_projeto_code, 
				a1.as_projeto_status,
				a1.as_projeto_cliente_nome,
				a1.as_projeto_nome,
				a1.as_fat_valor_total_as_liquido,
				a1.as_fat_valor_total_as_bruto,
				a1.create_time,
				v.vendedor_nome, 
				c.cat_name
			FROM autorizacao_servico a1 
				INNER JOIN (SELECT MAX(a2.id) as id FROM autorizacao_servico a2 GROUP BY a2.id_revisao) a2
				ON a2.id = a1.id
				INNER JOIN vendedores v 
				ON v.id = a1.id_vendedor
				LEFT JOIN categorias c 
				ON c.id = a1.id_categoria
			WHERE a1.active = 'Y' 
			
		";

		// Filtros

		// Status
		if(isset($args['as_status'])){
			if($args['as_status'] != '0'){
				$query .= " AND a1.as_projeto_status = '".$args['as_status']."' ";
			}
		}

		// Categoria
		if(isset($args['id_categoria'])){
			if($args['id_categoria'] != '0'){
				$query .= " AND a1.id_categoria = '".$args['id_categoria']."' ";
			}
		}

		// Date range
		if(isset($args['init']) && isset($args['end'])){

			$date = new \DateTime();

			$date->setTimestamp($args['init']);
			$init = $date->format("Y-m-d 23:59:59");

			$date->setTimestamp($args['end']);
			$end = $date->format("Y-m-d 23:59:59");

			$query .= " AND a1.create_time >= '".$init."' AND a1.create_time <= '".$end."' ";
			
		}

		$query .= " ORDER BY a1.create_time DESC ";

		$select = $this->db->query($query);
		$contents = $select->fetchAll(\PDO::FETCH_ASSOC);
		$contents_list = array(
			"as_projeto_code",
			"as_projeto_status",
			"as_projeto_cliente_nome",
			"as_projeto_nome",
			"as_fat_valor_total_as_liquido",
			"as_fat_valor_total_as_bruto",
			"as_fat_valor_total_as_bruto",
			"as_fat_valor_total_as_bruto",
			"vendedor_nome",
			"cat_name",
			"create_time"
		);

		// Monta arquivo em EXCEL

		$alphabet = range('A', 'Z');

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		foreach ($header as $key => $value) {
			$sheet->setCellValue($alphabet[$key] . '1', $value);
			$sheet->getColumnDimension($alphabet[$key])->setAutoSize(true);
		}

		// Cor do fundo do header
		$sheet->getStyle('A1:'.$alphabet[$key].'1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('3b3f51');

		// Cor da fonte do header
		$sheet->getStyle('A1:'.$alphabet[$key].'1')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);

		// Adiciona conteúdo 

		$content_line = 2;

		foreach ($contents as $key => $value) {
			foreach ($contents_list as $col_key => $campo) {
				$sheet->setCellValue($alphabet[$col_key] . $content_line, $value[$campo]);
			}
			$content_line++;
		}

		// Cria arquivo

		$dir = $this->get_directory();

		if($dir['result']){

			$filename = 'relatorio-as-'.date('d-m-Y-H-i-s').'.xlsx';
			$dir_file = $dir['path'].$filename;

			$writer = new Xlsx($spreadsheet);
			$writer->save($dir_file);

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

		$uri = '/relatorios/'.date('Y').'/'.date('m').'/';

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


	// COTAÇÕES - STATUS
	public function generate_cotacao_status($args){

		$response = array(
			'result' => false
		);

		$cotacao = new Cotacoes($this->db);
		$cotacoes_objetos = new CotacoesObjetos($this->db);

		// Gera o arquivo
		$mpdf = new \Mpdf\Mpdf([			
			'margin_top' => '28'
		]);

		// Header
		$mpdf->SetHTMLHeader('
			<table cellpadding="0" cellspacing="0" width="100%" style="width: 100%; border-bottom:1px solid #000;">
				<tr>
					<td><img src="logo.png"></td>
					<td width="220">
						<center>
							<table style="width: 220px;" width="220">
								<tr>
									<td style="border:1px solid #000;  font-size: 12px; padding: 4px 5px;">
										<strong>Relatório de Cotações por Status</strong>
									</td>
								</tr>
							</table>
						</center>
					</td>
				</tr>
			</table>
		');

		// Footer
		$mpdf->SetFooter("Página {PAGENO} de {nb}");

		$query = "
			SELECT 
				cm.*,
				v.vendedor_nome
			FROM cotacoes cm
				INNER JOIN (SELECT MAX(c.id) as id FROM cotacoes c GROUP BY c.id_revisao) c 
				ON c.id = cm.id

				LEFT OUTER JOIN vendedores v 
				ON v.id = cm.id_vendedor

			WHERE cm.active = 'Y' 
			AND cm.cotacao_status = '".$args['cotacao_status']."' 
		";

		// Date range
		if(isset($args['init']) && isset($args['end'])){

			$date = new \DateTime();

			$date->setTimestamp($args['init']);
			$init = $date->format("Y-m-d 00:00:00");

			$date->setTimestamp($args['end']);
			$end = $date->format("Y-m-d 23:59:59");

			$query .= " AND cm.create_time >= '".$init."' AND cm.create_time <= '".$end."' ";
			
		}

		$query .= " ORDER BY cm.create_time DESC;";

		$select = $this->db->query($query);
		$contents = $select->fetchAll(\PDO::FETCH_ASSOC);
		$cotacoes_list = '';

		$date = new \DateTime();

		foreach ($contents as $key => $value) {

			$bgcolor = (($key % 2) == 1 ? 'bgcolor="#ddebf7"' : '');

			$date = new \DateTime($value['create_time']);

			$objetos = $cotacoes_objetos->get(array('id_cotacao' => $value['id'] ));

			$cotacoes_list .= '<tr>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$date->format('d/m/Y H:i').'</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$value['cotacao_cliente_nome'].'</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$value['cotacao_projeto_nome'].'</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center"> ';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$cotacoes_list .= '<p>'.$objeto['objeto_origem_cidade'].'-'.$objeto['objeto_origem_uf'].'</p>';
					}

				$cotacoes_list .= '</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center"> ';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$cotacoes_list .= '<p>'.$objeto['objeto_destino_cidade'].'-'.$objeto['objeto_destino_uf'].'</p>';
					}

				$cotacoes_list .= '</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center">';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$cotacoes_list .= '<p>'.
							$objeto['objeto_comprimento'].'x'.
							$objeto['objeto_largura'].'x'.
							$objeto['objeto_altura'].'<br/>'.
							$objeto['objeto_peso_unit'].
							'</p>';
					}

				$cotacoes_list .= '</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$value['vendedor_nome'].'</td>';
			$cotacoes_list .= '</tr>';
		}

		$content = Utilities::file_reader($this->relatorios_template_path.'cotacoes-status.html',array(
			'|*COTACOES_LIST*|' => $cotacoes_list,
			'|*STATUS*|' => $cotacao->cotacao_status_array[$args['cotacao_status']],
			'|*PERIODO*|' => date('d/m/Y', $args['init']).' - '.date('d/m/Y', $args['end']),
			'|*EMISSAO*|' => date('d/m/Y H:i')
		));

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


	// COTAÇÕES - VENDEDORES
	public function generate_cotacao_vendedores($args){

		$response = array(
			'result' => false
		);

		$cotacao = new Cotacoes($this->db);
		$cotacoes_objetos = new CotacoesObjetos($this->db);

		// Gera o arquivo
		$mpdf = new \Mpdf\Mpdf([			
			'margin_top' => '28'
		]);

		// Header
		$mpdf->SetHTMLHeader('
			<table cellpadding="0" cellspacing="0" width="100%" style="width: 100%; border-bottom:1px solid #000;">
				<tr>
					<td><img src="logo.png"></td>
					<td width="220">
						<center>
							<table style="width: 220px;" width="220">
								<tr>
									<td style="border:1px solid #000;  font-size: 12px; padding: 4px 5px;">
										<strong>Relatório de Cotações por Vendedor</strong>
									</td>
								</tr>
							</table>
						</center>
					</td>
				</tr>
			</table>
		');

		// Footer
		$mpdf->SetFooter("Página {PAGENO} de {nb}");

		$query = "
			SELECT 
				cm.*,
				v.vendedor_nome
			FROM cotacoes cm
				INNER JOIN (SELECT MAX(c.id) as id FROM cotacoes c GROUP BY c.id_revisao) c 
				ON c.id = cm.id

				LEFT OUTER JOIN vendedores v 
				ON v.id = cm.id_vendedor

			WHERE cm.active = 'Y' 
			AND cm.id_vendedor = '".$args['id_vendedor']."' 
		";

		// Date range
		if(isset($args['init']) && isset($args['end'])){

			$date = new \DateTime();

			$date->setTimestamp($args['init']);
			$init = $date->format("Y-m-d 00:00:00");

			$date->setTimestamp($args['end']);
			$end = $date->format("Y-m-d 23:59:59");

			$query .= " AND cm.create_time >= '".$init."' AND cm.create_time <= '".$end."' ";
			
		}

		$query .= " ORDER BY cm.create_time DESC;";

		$select = $this->db->query($query);
		$contents = $select->fetchAll(\PDO::FETCH_ASSOC);
		$cotacoes_list = '';

		$date = new \DateTime();
		
		foreach ($contents as $key => $value) {

			$bgcolor = (($key % 2) == 1 ? 'bgcolor="#ddebf7"' : '');

			$date = new \DateTime($value['create_time']);

			$objetos = $cotacoes_objetos->get(array('id_cotacao' => $value['id'] ));

			$cotacoes_list .= '<tr>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$date->format('d/m/Y H:i').'</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$value['cotacao_cliente_nome'].'</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$value['cotacao_projeto_nome'].'</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center"> ';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$cotacoes_list .= '<p>'.$objeto['objeto_origem_cidade'].'-'.$objeto['objeto_origem_uf'].'</p>';
					}

				$cotacoes_list .= '</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center"> ';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$cotacoes_list .= '<p>'.$objeto['objeto_destino_cidade'].'-'.$objeto['objeto_destino_uf'].'</p>';
					}

				$cotacoes_list .= '</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center">';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$cotacoes_list .= '<p>'.
							$objeto['objeto_comprimento'].'x'.
							$objeto['objeto_largura'].'x'.
							$objeto['objeto_altura'].'<br/>'.
							$objeto['objeto_peso_unit'].
							'</p>';
					}

				$cotacoes_list .= '</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$cotacao->cotacao_status_array[$value['cotacao_status']] .'</td>';
			$cotacoes_list .= '</tr>';
		}

		$content = Utilities::file_reader($this->relatorios_template_path.'cotacoes-vendedores.html',array(
			'|*COTACOES_LIST*|' => $cotacoes_list,
			'|*VENDEDOR*|' => $value['vendedor_nome'],
			'|*PERIODO*|' => date('d/m/Y', $args['init']).' - '.date('d/m/Y', $args['end']),
			'|*EMISSAO*|' => date('d/m/Y H:i')
		));

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


	// COTAÇÕES - PERÍODO
	public function generate_cotacao_periodo($args){

		$response = array(
			'result' => false
		);

		$cotacao = new Cotacoes($this->db);
		$cotacoes_objetos = new CotacoesObjetos($this->db);

		// Gera o arquivo
		$mpdf = new \Mpdf\Mpdf([			
			'margin_top' => '28'
		]);

		// Header
		$mpdf->SetHTMLHeader('
			<table cellpadding="0" cellspacing="0" width="100%" style="width: 100%; border-bottom:1px solid #000;">
				<tr>
					<td><img src="logo.png"></td>
					<td width="220">
						<center>
							<table style="width: 220px;" width="220">
								<tr>
									<td style="border:1px solid #000;  font-size: 12px; padding: 4px 5px;">
										<strong>Relatório de Cotações por Período</strong>
									</td>
								</tr>
							</table>
						</center>
					</td>
				</tr>
			</table>
		');

		// Footer
		$mpdf->SetFooter("Página {PAGENO} de {nb}");

		$query = "
			SELECT 
				cm.*,
				v.vendedor_nome
			FROM cotacoes cm
				INNER JOIN (SELECT MAX(c.id) as id FROM cotacoes c GROUP BY c.id_revisao) c 
				ON c.id = cm.id

				LEFT OUTER JOIN vendedores v 
				ON v.id = cm.id_vendedor

			WHERE cm.active = 'Y' 
		";

		// Date range
		if(isset($args['init']) && isset($args['end'])){

			$date = new \DateTime();

			$date->setTimestamp($args['init']);
			$init = $date->format("Y-m-d 00:00:00");

			$date->setTimestamp($args['end']);
			$end = $date->format("Y-m-d 23:59:59");

			$query .= " AND cm.create_time >= '".$init."' AND cm.create_time <= '".$end."' ";
			
		}

		$query .= " ORDER BY cm.create_time DESC;";

		$select = $this->db->query($query);
		$contents = $select->fetchAll(\PDO::FETCH_ASSOC);
		$cotacoes_list = '';

		$date = new \DateTime();
		
		foreach ($contents as $key => $value) {

			$bgcolor = (($key % 2) == 1 ? 'bgcolor="#ddebf7"' : '');

			$date = new \DateTime($value['create_time']);

			$objetos = $cotacoes_objetos->get(array('id_cotacao' => $value['id'] ));

			$cotacoes_list .= '<tr>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$date->format('d/m/Y H:i').'</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$value['cotacao_cliente_nome'].'</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$value['cotacao_projeto_nome'].'</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center"> ';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$cotacoes_list .= '<p>'.$objeto['objeto_origem_cidade'].'-'.$objeto['objeto_origem_uf'].'</p>';
					}

				$cotacoes_list .= '</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center"> ';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$cotacoes_list .= '<p>'.$objeto['objeto_destino_cidade'].'-'.$objeto['objeto_destino_uf'].'</p>';
					}

				$cotacoes_list .= '</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center">';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$cotacoes_list .= '<p>'.
							$objeto['objeto_comprimento'].'x'.
							$objeto['objeto_largura'].'x'.
							$objeto['objeto_altura'].'<br/>'.
							$objeto['objeto_peso_unit'].
							'</p>';
					}

				$cotacoes_list .= '</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$cotacao->cotacao_status_array[$value['cotacao_status']] .'</td>';
			$cotacoes_list .= '</tr>';
		}

		$content = Utilities::file_reader($this->relatorios_template_path.'cotacoes-periodo.html',array(
			'|*COTACOES_LIST*|' => $cotacoes_list,
			'|*PERIODO*|' => date('d/m/Y', $args['init']).' - '.date('d/m/Y', $args['end']),
			'|*EMISSAO*|' => date('d/m/Y H:i')
		));

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

	// COTAÇÕES - CLIENTE
	public function generate_cotacao_cliente($args){

		$response = array(
			'result' => false
		);

		$cotacao = new Cotacoes($this->db);
		$cotacoes_objetos = new CotacoesObjetos($this->db);

		// Gera o arquivo
		$mpdf = new \Mpdf\Mpdf([			
			'margin_top' => '28'
		]);

		// Header
		$mpdf->SetHTMLHeader('
			<table cellpadding="0" cellspacing="0" width="100%" style="width: 100%; border-bottom:1px solid #000;">
				<tr>
					<td><img src="logo.png"></td>
					<td width="220">
						<center>
							<table style="width: 220px;" width="220">
								<tr>
									<td style="border:1px solid #000;  font-size: 12px; padding: 4px 5px;">
										<strong>Relatório de Cotações por Cliente</strong>
									</td>
								</tr>
							</table>
						</center>
					</td>
				</tr>
			</table>
		');

		// Footer
		$mpdf->SetFooter("Página {PAGENO} de {nb}");

		$query = "
			SELECT 
				cm.*,
				v.vendedor_nome
			FROM cotacoes cm
				INNER JOIN (SELECT MAX(c.id) as id FROM cotacoes c GROUP BY c.id_revisao) c 
				ON c.id = cm.id

				LEFT OUTER JOIN vendedores v 
				ON v.id = cm.id_vendedor

			WHERE cm.active = 'Y' 
			AND cm.id_cliente = '".$args['id_cliente']."'
		";

		// Date range
		if(isset($args['init']) && isset($args['end'])){

			$date = new \DateTime();

			$date->setTimestamp($args['init']);
			$init = $date->format("Y-m-d 00:00:00");

			$date->setTimestamp($args['end']);
			$end = $date->format("Y-m-d 23:59:59");

			$query .= " AND cm.create_time >= '".$init."' AND cm.create_time <= '".$end."' ";
			
		}

		$query .= " ORDER BY cm.create_time DESC;";

		$select = $this->db->query($query);
		$contents = $select->fetchAll(\PDO::FETCH_ASSOC);
		$cotacoes_list = '';

		$date = new \DateTime();
		
		foreach ($contents as $key => $value) {

			$bgcolor = (($key % 2) == 1 ? 'bgcolor="#ddebf7"' : '');

			$date = new \DateTime($value['create_time']);

			$objetos = $cotacoes_objetos->get(array('id_cotacao' => $value['id'] ));

			$cotacoes_list .= '<tr>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$date->format('d/m/Y H:i').'</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$value['cotacao_projeto_nome'].'</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$value['cotacao_projeto_descricao'].'</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center"> ';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$cotacoes_list .= '<p>'.$objeto['objeto_origem_cidade'].'-'.$objeto['objeto_origem_uf'].'</p>';
					}

				$cotacoes_list .= '</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center"> ';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$cotacoes_list .= '<p>'.$objeto['objeto_destino_cidade'].'-'.$objeto['objeto_destino_uf'].'</p>';
					}

				$cotacoes_list .= '</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center">';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$cotacoes_list .= '<p>'.
							$objeto['objeto_comprimento'].'x'.
							$objeto['objeto_largura'].'x'.
							$objeto['objeto_altura'].'<br/>'.
							$objeto['objeto_peso_unit'].
							'</p>';
					}

				$cotacoes_list .= '</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">R$'.number_format($objeto['objeto_valor_total'],2,',','.').'</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$cotacao->cotacao_status_array[$value['cotacao_status']] .'</td>';
			$cotacoes_list .= '</tr>';
		}

		$content = Utilities::file_reader($this->relatorios_template_path.'cotacoes-cliente.html',array(
			'|*COTACOES_LIST*|' => $cotacoes_list,
			'|*PERIODO*|' => date('d/m/Y', $args['init']).' - '.date('d/m/Y', $args['end']),
			'|*EMISSAO*|' => date('d/m/Y H:i'),
			'|*CLIENTE*|' => $value['cotacao_cliente_nome']
		));

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


	// COTAÇÕES - ORIGEM DESTINO
	public function generate_cotacao_origem_destino($args){

		$response = array(
			'result' => false
		);

		$cotacao = new Cotacoes($this->db);
		$cotacoes_objetos = new CotacoesObjetos($this->db);

		// Gera o arquivo
		$mpdf = new \Mpdf\Mpdf([			
			'margin_top' => '28'
		]);

		// Header
		$mpdf->SetHTMLHeader('
			<table cellpadding="0" cellspacing="0" width="100%" style="width: 100%; border-bottom:1px solid #000;">
				<tr>
					<td><img src="logo.png"></td>
					<td width="220">
						<center>
							<table style="width: 220px;" width="220">
								<tr>
									<td style="border:1px solid #000;  font-size: 12px; padding: 4px 5px;">
										<strong>Relatório de Cotações por Origem x Destino</strong>
									</td>
								</tr>
							</table>
						</center>
					</td>
				</tr>
			</table>
		');

		// Footer
		$mpdf->SetFooter("Página {PAGENO} de {nb}");

		$query = "
			SELECT 
				cm.*
			FROM cotacoes_objetos co 

				INNER JOIN (SELECT MAX(c.id) as id FROM cotacoes c GROUP BY c.id_revisao) c 
				ON c.id = co.id_cotacao

				INNER JOIN cotacoes cm
				ON c.id = cm.id
				
			WHERE cm.active = 'Y'
		";

		// Date range
		if(isset($args['origem'])){
			if($args['origem'] != '0'){
				$query .= " AND co.objeto_origem_uf = '".$args['origem']."' ";
			}
		}

		if(isset($args['destino'])){
			if($args['destino'] != '0'){
				$query .= " AND co.objeto_destino_uf = '".$args['destino']."' ";
			}
		}

		// Date range
		if(isset($args['init']) && isset($args['end'])){

			$date = new \DateTime();

			$date->setTimestamp($args['init']);
			$init = $date->format("Y-m-d 00:00:00");

			$date->setTimestamp($args['end']);
			$end = $date->format("Y-m-d 23:59:59");

			$query .= " AND cm.create_time >= '".$init."' AND cm.create_time <= '".$end."' ";
			
		}

		$query .= " ORDER BY cm.create_time DESC;";

		$response['query'] = $query;

		$select = $this->db->query($query);
		$contents = $select->fetchAll(\PDO::FETCH_ASSOC);
		$cotacoes_list = '';

		$date = new \DateTime();
		
		foreach ($contents as $key => $value) {

			$bgcolor = (($key % 2) == 1 ? 'bgcolor="#ddebf7"' : '');

			$date = new \DateTime($value['create_time']);

			$objetos = $cotacoes_objetos->get(array('id_cotacao' => $value['id'] ));

			$cotacoes_list .= '<tr>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$date->format('d/m/Y H:i').'</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$value['cotacao_projeto_nome'].'</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$value['cotacao_projeto_descricao'].'</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center"> ';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$cotacoes_list .= '<p>'.$objeto['objeto_origem_cidade'].'-'.$objeto['objeto_origem_uf'].'</p>';
					}

				$cotacoes_list .= '</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center"> ';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$cotacoes_list .= '<p>'.$objeto['objeto_destino_cidade'].'-'.$objeto['objeto_destino_uf'].'</p>';
					}

				$cotacoes_list .= '</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center">';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$cotacoes_list .= '<p>'.
							$objeto['objeto_comprimento'].'x'.
							$objeto['objeto_largura'].'x'.
							$objeto['objeto_altura'].'<br/>'.
							$objeto['objeto_peso_unit'].
							'</p>';
					}

				$cotacoes_list .= '</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center">R$'.number_format($objeto['objeto_valor_total'],2,',','.').'</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$cotacao->cotacao_status_array[$value['cotacao_status']] .'</td>';
			$cotacoes_list .= '</tr>';
		}

		$content = Utilities::file_reader($this->relatorios_template_path.'cotacoes-origem-destino.html',array(
			'|*COTACOES_LIST*|' => $cotacoes_list,
			'|*PERIODO*|' => date('d/m/Y', $args['init']).' - '.date('d/m/Y', $args['end']),
			'|*EMISSAO*|' => date('d/m/Y H:i'),
			'|*CLIENTE*|' => $value['cotacao_cliente_nome'],
			'|*ORIGEM*|' => ($args['origem'] != '0' ? $args['origem'] : '' ),
			'|*DESTINO*|' => ($args['destino'] != '0' ? $args['destino'] : '' )
		));

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


	// COTAÇÕES - DIMENSIONAL
	public function generate_cotacao_dimensional($args){

		$response = array(
			'result' => false
		);

		$cotacao = new Cotacoes($this->db);
		$cotacoes_objetos = new CotacoesObjetos($this->db);

		// Gera o arquivo
		$mpdf = new \Mpdf\Mpdf([			
			'margin_top' => '28'
		]);

		// Header
		$mpdf->SetHTMLHeader('
			<table cellpadding="0" cellspacing="0" width="100%" style="width: 100%; border-bottom:1px solid #000;">
				<tr>
					<td><img src="logo.png"></td>
					<td width="220">
						<center>
							<table style="width: 220px;" width="220">
								<tr>
									<td style="border:1px solid #000;  font-size: 12px; padding: 4px 5px;">
										<strong>Relatório de Cotações por Dimensional</strong>
									</td>
								</tr>
							</table>
						</center>
					</td>
				</tr>
			</table>
		');

		// Footer
		$mpdf->SetFooter("Página {PAGENO} de {nb}");

		$query = "
			SELECT 
				cm.*
			FROM cotacoes_objetos co 

				INNER JOIN (SELECT MAX(c.id) as id FROM cotacoes c GROUP BY c.id_revisao) c 
				ON c.id = co.id_cotacao

				INNER JOIN cotacoes cm
				ON c.id = cm.id
				
			WHERE cm.active = 'Y'
		";

		if(isset($args['altura'])){
			if(strlen($args['altura']) > 0){
				$query .= " AND co.objeto_altura = '".$args['altura']."' ";
			}
		}

		if(isset($args['largura'])){
			if(strlen($args['largura']) > 0){
				$query .= " AND co.objeto_largura = '".$args['largura']."' ";
			}
		}

		if(isset($args['comprimento'])){
			if(strlen($args['comprimento']) > 0){
				$query .= " AND co.objeto_comprimento = '".$args['comprimento']."' ";
			}
		}

		if(isset($args['peso'])){
			if(strlen($args['peso']) > 0){
				$query .= " AND co.objeto_peso_unit = '".$args['peso']."' ";
			}
		}

		// Date range
		if(isset($args['init']) && isset($args['end'])){

			$date = new \DateTime();

			$date->setTimestamp($args['init']);
			$init = $date->format("Y-m-d 00:00:00");

			$date->setTimestamp($args['end']);
			$end = $date->format("Y-m-d 23:59:59");

			$query .= " AND cm.create_time >= '".$init."' AND cm.create_time <= '".$end."' ";
			
		}

		$query .= " ORDER BY cm.create_time DESC;";

		$response['query'] = $query;

		$select = $this->db->query($query);
		$contents = $select->fetchAll(\PDO::FETCH_ASSOC);
		$cotacoes_list = '';

		$date = new \DateTime();
		
		foreach ($contents as $key => $value) {

			$bgcolor = (($key % 2) == 1 ? 'bgcolor="#ddebf7"' : '');

			$date = new \DateTime($value['create_time']);

			$objetos = $cotacoes_objetos->get(array('id_cotacao' => $value['id'] ));

			$cotacoes_list .= '<tr>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$date->format('d/m/Y H:i').'</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$value['cotacao_projeto_nome'].'</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$value['cotacao_projeto_descricao'].'</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center"> ';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$cotacoes_list .= '<p>'.$objeto['objeto_origem_cidade'].'-'.$objeto['objeto_origem_uf'].'</p>';
					}

				$cotacoes_list .= '</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center"> ';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$cotacoes_list .= '<p>'.$objeto['objeto_destino_cidade'].'-'.$objeto['objeto_destino_uf'].'</p>';
					}

				$cotacoes_list .= '</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center">';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$cotacoes_list .= '<p>'.
							$objeto['objeto_comprimento'].'x'.
							$objeto['objeto_largura'].'x'.
							$objeto['objeto_altura'].'<br/>'.
							$objeto['objeto_peso_unit'].
							'</p>';
					}

				$cotacoes_list .= '</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center">R$'.number_format($objeto['objeto_valor_total'],2,',','.').'</td>';
				$cotacoes_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$cotacao->cotacao_status_array[$value['cotacao_status']] .'</td>';
			$cotacoes_list .= '</tr>';
		}

		$content = Utilities::file_reader($this->relatorios_template_path.'cotacoes-dimensional.html',array(
			'|*COTACOES_LIST*|' => $cotacoes_list,
			'|*PERIODO*|' => date('d/m/Y', $args['init']).' - '.date('d/m/Y', $args['end']),
			'|*EMISSAO*|' => date('d/m/Y H:i'),
			'|*CLIENTE*|' => $value['cotacao_cliente_nome'],
			
			'|*ALTURA*|' => $args['altura'],
			'|*LARGURA*|' => $args['largura'],
			'|*COMPRIMENTO*|' => $args['comprimento'],
			'|*PESO*|' => $args['peso'],

		));

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


	// PROJETOS - CLIENTE
	public function generate_projeto_cliente($args){

		$response = array(
			'result' => false
		);

		// Gera o arquivo
		$mpdf = new \Mpdf\Mpdf([			
			'margin_top' => '28'
		]);

		$projetos = new Projetos($this->db);
		$cotacoes_objetos = new CotacoesObjetos($this->db);

		// Header
		$mpdf->SetHTMLHeader('
			<table cellpadding="0" cellspacing="0" width="100%" style="width: 100%; border-bottom:1px solid #000;">
				<tr>
					<td><img src="logo.png"></td>
					<td width="220">
						<center>
							<table style="width: 220px;" width="220">
								<tr>
									<td style="border:1px solid #000;  font-size: 12px; padding: 4px 5px;">
										<strong>Relatório de Projetos por Cliente</strong>
									</td>
								</tr>
							</table>
						</center>
					</td>
				</tr>
			</table>
		');

		// Footer
		$mpdf->SetFooter("Página {PAGENO} de {nb}");

		$query = "
			SELECT 
				p.*
			FROM projetos p

			WHERE p.active = 'Y' 
			AND p.id_cliente = '".$args['id_cliente']."'
		";

		// Date range
		if(isset($args['init']) && isset($args['end'])){

			$date = new \DateTime();

			$date->setTimestamp($args['init']);
			$init = $date->format("Y-m-d 00:00:00");

			$date->setTimestamp($args['end']);
			$end = $date->format("Y-m-d 23:59:59");

			$query .= " AND p.create_time >= '".$init."' AND p.create_time <= '".$end."' ";
			
		}

		$query .= " ORDER BY p.create_time DESC;";

		$select = $this->db->query($query);
		$contents = $select->fetchAll(\PDO::FETCH_ASSOC);
		$projeto_list = '';

		$date = new \DateTime();
		
		foreach ($contents as $key => $value) {

			$bgcolor = (($key % 2) == 1 ? 'bgcolor="#ddebf7"' : '');

			$date = new \DateTime($value['create_time']);

			$objetos = $cotacoes_objetos->get(array('id_cotacao' => $value['id_cotacao'] ));

			$projeto_list .= '<tr>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$date->format('d/m/Y H:i').'</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$value['projeto_nome'].'</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center"> ';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$projeto_list .= '<p>'.$objeto['objeto_origem_cidade'].'-'.$objeto['objeto_origem_uf'].'</p>';
					}

				$projeto_list .= '</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center"> ';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$projeto_list .= '<p>'.$objeto['objeto_destino_cidade'].'-'.$objeto['objeto_destino_uf'].'</p>';
					}

				$projeto_list .= '</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center">';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$projeto_list .= '<p>'.
							$objeto['objeto_comprimento'].'x'.
							$objeto['objeto_largura'].'x'.
							$objeto['objeto_altura'].'<br/>'.
							$objeto['objeto_peso_unit'].
							'</p>';
					}

				$projeto_list .= '</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;"></td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;"></td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;"></td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;"></td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;"></td>';
			$projeto_list .= '</tr>';
		}

		$content = Utilities::file_reader($this->relatorios_template_path.'projeto-cliente.html',array(
			'|*PROJETOS_LIST*|' => $projeto_list,
			'|*PERIODO*|' => date('d/m/Y', $args['init']).' - '.date('d/m/Y', $args['end']),
			'|*EMISSAO*|' => date('d/m/Y H:i'),
			'|*CLIENTE*|' => $value['projeto_cliente_nome']
		));

		$mpdf->WriteHTML($content);

		$mpdf->shrink_tables_to_fit = 1;

		// Salva o arquivo
		$dir = $this->get_directory();

		if($dir['result']){

			$filename = 'projeto-'.date('d-m-Y-H-i-s').'.pdf';
			$dir_file = $dir['path'].$filename;

			$mpdf->Output($dir_file);

			$response['file'] = $dir['uri'].$filename;
			$response['result'] = true;

		}else{
			$response['error'] = $dir['error'];
		}		

		return $response;

	}

	// PROJETOS - CUSTO
	public function generate_projeto_custo($args){

		$response = array(
			'result' => false
		);

		// Gera o arquivo
		$mpdf = new \Mpdf\Mpdf([			
			'margin_top' => '28'
		]);

		// Header
		$mpdf->SetHTMLHeader('
			<table cellpadding="0" cellspacing="0" width="100%" style="width: 100%; border-bottom:1px solid #000;">
				<tr>
					<td><img src="logo.png"></td>
					<td width="220">
						<center>
							<table style="width: 220px;" width="220">
								<tr>
									<td style="border:1px solid #000;  font-size: 12px; padding: 4px 5px;">
										<strong>Relatório de Projetos<br />CUSTO</strong>
									</td>
								</tr>
							</table>
						</center>
					</td>
				</tr>
			</table>
		');

		// Footer
		$mpdf->SetFooter("Página {PAGENO} de {nb}");

		$query = "
			SELECT 
				p.*
			FROM projetos p
			WHERE p.active = 'Y' 
			AND p.id = '".$args['id_projeto']."'
		";

		$select = $this->db->query($query);
		$content = $select->fetch(\PDO::FETCH_ASSOC);
		$projeto_list = '';

		$date = new \DateTime();
		
		$content = Utilities::file_reader($this->relatorios_template_path.'projeto-custo.html',array(
			'|*PROJETOS_LIST*|' => $projeto_list,
			'|*EMISSAO*|' => date('d/m/Y H:i'),
			'|*CLIENTE*|' => $value['projeto_cliente_nome']
		));

		$mpdf->WriteHTML($content);

		$mpdf->shrink_tables_to_fit = 1;

		// Salva o arquivo
		$dir = $this->get_directory();

		if($dir['result']){

			$filename = 'projeto-'.date('d-m-Y-H-i-s').'.pdf';
			$dir_file = $dir['path'].$filename;

			$mpdf->Output($dir_file);

			$response['file'] = $dir['uri'].$filename;
			$response['result'] = true;

		}else{
			$response['error'] = $dir['error'];
		}		

		return $response;

	}

	// PROJETOS - PERÍODO
	public function generate_projeto_periodo($args){

		$response = array(
			'result' => false
		);

		// Gera o arquivo
		$mpdf = new \Mpdf\Mpdf([			
			'margin_top' => '28'
		]);

		$projetos = new Projetos($this->db);
		$cotacoes_objetos = new CotacoesObjetos($this->db);

		// Header
		$mpdf->SetHTMLHeader('
			<table cellpadding="0" cellspacing="0" width="100%" style="width: 100%; border-bottom:1px solid #000;">
				<tr>
					<td><img src="logo.png"></td>
					<td width="220">
						<center>
							<table style="width: 220px;" width="220">
								<tr>
									<td style="border:1px solid #000;  font-size: 12px; padding: 4px 5px;">
										<strong>Relatório de Projetos por Período</strong>
									</td>
								</tr>
							</table>
						</center>
					</td>
				</tr>
			</table>
		');

		// Footer
		$mpdf->SetFooter("Página {PAGENO} de {nb}");

		$query = "
			SELECT 
				p.*
			FROM projetos p
			WHERE p.active = 'Y' 
		";

		// Date range
		if(isset($args['init']) && isset($args['end'])){

			$date = new \DateTime();

			$date->setTimestamp($args['init']);
			$init = $date->format("Y-m-d 00:00:00");

			$date->setTimestamp($args['end']);
			$end = $date->format("Y-m-d 23:59:59");

			$query .= " AND p.create_time >= '".$init."' AND p.create_time <= '".$end."' ";
			
		}

		$query .= " ORDER BY p.create_time DESC;";

		$select = $this->db->query($query);
		$contents = $select->fetchAll(\PDO::FETCH_ASSOC);
		$projeto_list = '';

		$date = new \DateTime();
		
		foreach ($contents as $key => $value) {

			$bgcolor = (($key % 2) == 1 ? 'bgcolor="#ddebf7"' : '');

			$date = new \DateTime($value['create_time']);

			$objetos = $cotacoes_objetos->get(array('id_cotacao' => $value['id_cotacao'] ));

			$projeto_list .= '<tr>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$date->format('d/m/Y H:i').'</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$value['projeto_cliente_nome'].'</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$value['projeto_nome'].'</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center"> ';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$projeto_list .= '<p>'.$objeto['objeto_origem_cidade'].'-'.$objeto['objeto_origem_uf'].'</p>';
					}

				$projeto_list .= '</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center"> ';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$projeto_list .= '<p>'.$objeto['objeto_destino_cidade'].'-'.$objeto['objeto_destino_uf'].'</p>';
					}

				$projeto_list .= '</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center">';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$projeto_list .= '<p>'.
							$objeto['objeto_comprimento'].'x'.
							$objeto['objeto_largura'].'x'.
							$objeto['objeto_altura'].'<br/>'.
							$objeto['objeto_peso_unit'].
							'</p>';
					}

				$projeto_list .= '</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;"></td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;"></td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;"></td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;"></td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;"></td>';
			$projeto_list .= '</tr>';
		}

		$content = Utilities::file_reader($this->relatorios_template_path.'projeto-periodo.html',array(
			'|*PROJETOS_LIST*|' => $projeto_list,
			'|*PERIODO*|' => date('d/m/Y', $args['init']).' - '.date('d/m/Y', $args['end']),
			'|*EMISSAO*|' => date('d/m/Y H:i'),
		));

		$mpdf->WriteHTML($content);

		$mpdf->shrink_tables_to_fit = 1;

		// Salva o arquivo
		$dir = $this->get_directory();

		if($dir['result']){

			$filename = 'projeto-'.date('d-m-Y-H-i-s').'.pdf';
			$dir_file = $dir['path'].$filename;

			$mpdf->Output($dir_file);

			$response['file'] = $dir['uri'].$filename;
			$response['result'] = true;

		}else{
			$response['error'] = $dir['error'];
		}		

		return $response;

	}


	// PROJETOS - CATEGORIA
	public function generate_projeto_categoria($args){

		$response = array(
			'result' => false
		);

		// Gera o arquivo
		$mpdf = new \Mpdf\Mpdf([			
			'margin_top' => '28'
		]);

		$projetos = new Projetos($this->db);
		$cotacoes_objetos = new CotacoesObjetos($this->db);
		$categorias = new Categorias($this->db);

		// Header
		$mpdf->SetHTMLHeader('
			<table cellpadding="0" cellspacing="0" width="100%" style="width: 100%; border-bottom:1px solid #000;">
				<tr>
					<td><img src="logo.png"></td>
					<td width="220">
						<center>
							<table style="width: 220px;" width="220">
								<tr>
									<td style="border:1px solid #000;  font-size: 12px; padding: 4px 5px;">
										<strong>Relatório de Projetos por Categoria</strong>
									</td>
								</tr>
							</table>
						</center>
					</td>
				</tr>
			</table>
		');

		// Footer
		$mpdf->SetFooter("Página {PAGENO} de {nb}");

		$query = "
			SELECT 
				p.*
			FROM projetos p
			WHERE p.active = 'Y' 
			AND p.id_categoria = '".$args['id_categoria']."'
		";

		// Date range
		if(isset($args['init']) && isset($args['end'])){

			$date = new \DateTime();

			$date->setTimestamp($args['init']);
			$init = $date->format("Y-m-d 00:00:00");

			$date->setTimestamp($args['end']);
			$end = $date->format("Y-m-d 23:59:59");

			$query .= " AND p.create_time >= '".$init."' AND p.create_time <= '".$end."' ";
			
		}

		$query .= " ORDER BY p.create_time DESC;";

		$select = $this->db->query($query);
		$contents = $select->fetchAll(\PDO::FETCH_ASSOC);
		$projeto_list = '';

		$date = new \DateTime();
		
		foreach ($contents as $key => $value) {

			$bgcolor = (($key % 2) == 1 ? 'bgcolor="#ddebf7"' : '');

			$date = new \DateTime($value['create_time']);

			$objetos = $cotacoes_objetos->get(array('id_cotacao' => $value['id_cotacao'] ));

			$projeto_list .= '<tr>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$date->format('d/m/Y H:i').'</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$value['projeto_cliente_nome'].'</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$value['projeto_nome'].'</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center"> ';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$projeto_list .= '<p>'.$objeto['objeto_origem_cidade'].'-'.$objeto['objeto_origem_uf'].'</p>';
					}

				$projeto_list .= '</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center"> ';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$projeto_list .= '<p>'.$objeto['objeto_destino_cidade'].'-'.$objeto['objeto_destino_uf'].'</p>';
					}

				$projeto_list .= '</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center">';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$projeto_list .= '<p>'.
							$objeto['objeto_comprimento'].'x'.
							$objeto['objeto_largura'].'x'.
							$objeto['objeto_altura'].'<br/>'.
							$objeto['objeto_peso_unit'].
							'</p>';
					}

				$projeto_list .= '</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;"></td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;"></td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;"></td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;"></td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;"></td>';
			$projeto_list .= '</tr>';
		}

		// Categoria 
		$categoria = $categorias->get_by_id($args['id_categoria']);

		$content = Utilities::file_reader($this->relatorios_template_path.'projeto-categoria.html',array(
			'|*PROJETOS_LIST*|' => $projeto_list,
			'|*PERIODO*|' => date('d/m/Y', $args['init']).' - '.date('d/m/Y', $args['end']),
			'|*EMISSAO*|' => date('d/m/Y H:i'),
			'|*CATEGORIA*|' => $categoria['categoria']['cat_name'],
		));

		$mpdf->WriteHTML($content);

		$mpdf->shrink_tables_to_fit = 1;

		// Salva o arquivo
		$dir = $this->get_directory();

		if($dir['result']){

			$filename = 'projeto-'.date('d-m-Y-H-i-s').'.pdf';
			$dir_file = $dir['path'].$filename;

			$mpdf->Output($dir_file);

			$response['file'] = $dir['uri'].$filename;
			$response['result'] = true;

		}else{
			$response['error'] = $dir['error'];
		}		

		return $response;

	}


	// AS - CLIENTE
	public function generate_as_cliente($args){

		$response = array(
			'result' => false
		);

		// Gera o arquivo
		$mpdf = new \Mpdf\Mpdf([			
			'margin_top' => '28'
		]);

		$as_objetos = new AsObjetos($this->db);

		// Header
		$mpdf->SetHTMLHeader('
			<table cellpadding="0" cellspacing="0" width="100%" style="width: 100%; border-bottom:1px solid #000;">
				<tr>
					<td><img src="logo.png"></td>
					<td width="220">
						<center>
							<table style="width: 220px;" width="220">
								<tr>
									<td style="border:1px solid #000;  font-size: 12px; padding: 4px 5px;">
										<strong>Relatório de AS por Cliente</strong>
									</td>
								</tr>
							</table>
						</center>
					</td>
				</tr>
			</table>
		');

		// Footer
		$mpdf->SetFooter("Página {PAGENO} de {nb}");

		$query = "
			SELECT 
				am.*,
				c.cat_name
			FROM autorizacao_servico am

				INNER JOIN (SELECT MAX(a.id) as id FROM autorizacao_servico a GROUP BY a.id_revisao) a 
				ON a.id = am.id

				LEFT OUTER JOIN categorias c
				ON c.id = am.id_categoria

			WHERE am.active = 'Y' 
			AND am.id_cliente = '".$args['id_cliente']."' 
		";

		// Date range
		if(isset($args['init']) && isset($args['end'])){

			$date = new \DateTime();

			$date->setTimestamp($args['init']);
			$init = $date->format("Y-m-d 00:00:00");

			$date->setTimestamp($args['end']);
			$end = $date->format("Y-m-d 23:59:59");

			$query .= " AND am.create_time >= '".$init."' AND am.create_time <= '".$end."' ";
			
		}

		$query .= " ORDER BY am.create_time DESC;";

		$select = $this->db->query($query);
		$contents = $select->fetchAll(\PDO::FETCH_ASSOC);
		$projeto_list = '';

		$date = new \DateTime();
		
		foreach ($contents as $key => $value) {

			$bgcolor = (($key % 2) == 1 ? 'bgcolor="#ddebf7"' : '');

			$date = new \DateTime($value['create_time']);

			$objetos = $as_objetos->get(array('id_as' => $value['id_as'] ));

			$projeto_list .= '<tr>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$date->format('d/m/Y H:i').'</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$value['as_projeto_code'].'</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$value['as_projeto_nome'].'</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center"> ';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$projeto_list .= '<p>'.$objeto['objeto_origem_cidade'].'-'.$objeto['objeto_origem_uf'].'</p>';
					}

				$projeto_list .= '</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center"> ';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$projeto_list .= '<p>'.$objeto['objeto_destino_cidade'].'-'.$objeto['objeto_destino_uf'].'</p>';
					}

				$projeto_list .= '</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center">';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$projeto_list .= '<p>'.
							$objeto['objeto_comprimento'].'x'.
							$objeto['objeto_largura'].'x'.
							$objeto['objeto_altura'].'<br/>'.
							$objeto['objeto_peso_unit'].
							'</p>';
					}

				$projeto_list .= '</td>';				
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$value['cat_name'].'</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;"></td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;"></td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;"></td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;"></td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;"></td>';
			$projeto_list .= '</tr>';
		}


		$content = Utilities::file_reader($this->relatorios_template_path.'as-cliente.html',array(
			'|*PROJETOS_LIST*|' => $projeto_list,
			'|*PERIODO*|' => date('d/m/Y', $args['init']).' - '.date('d/m/Y', $args['end']),
			'|*EMISSAO*|' => date('d/m/Y H:i'),
			'|*CLIENTE*|' => $value['as_projeto_cliente_nome'],
		));

		$mpdf->WriteHTML($content);

		$mpdf->shrink_tables_to_fit = 1;

		// Salva o arquivo
		$dir = $this->get_directory();

		if($dir['result']){

			$filename = 'projeto-'.date('d-m-Y-H-i-s').'.pdf';
			$dir_file = $dir['path'].$filename;

			$mpdf->Output($dir_file);

			$response['file'] = $dir['uri'].$filename;
			$response['result'] = true;

		}else{
			$response['error'] = $dir['error'];
		}		

		return $response;

	}


	// AS - PROJETO
	public function generate_as_projeto($args){

		$response = array(
			'result' => false
		);

		// Gera o arquivo
		$mpdf = new \Mpdf\Mpdf([			
			'margin_top' => '28'
		]);

		$as_objetos = new AsObjetos($this->db);

		// Header
		$mpdf->SetHTMLHeader('
			<table cellpadding="0" cellspacing="0" width="100%" style="width: 100%; border-bottom:1px solid #000;">
				<tr>
					<td><img src="logo.png"></td>
					<td width="220">
						<center>
							<table style="width: 220px;" width="220">
								<tr>
									<td style="border:1px solid #000;  font-size: 12px; padding: 4px 5px;">
										<strong>Relatório de AS por Projeto</strong>
									</td>
								</tr>
							</table>
						</center>
					</td>
				</tr>
			</table>
		');

		// Footer
		$mpdf->SetFooter("Página {PAGENO} de {nb}");

		$query = "
			SELECT 
				am.*,
				c.cat_name
			FROM autorizacao_servico am

				INNER JOIN (SELECT MAX(a.id) as id FROM autorizacao_servico a GROUP BY a.id_revisao) a 
				ON a.id = am.id

				LEFT OUTER JOIN categorias c
				ON c.id = am.id_categoria

			WHERE am.active = 'Y' 
			AND am.id_projeto = '".$args['id_projeto']."' 
			ORDER BY am.create_time DESC;
		";

		$select = $this->db->query($query);
		$contents = $select->fetchAll(\PDO::FETCH_ASSOC);
		$projeto_list = '';

		$date = new \DateTime();
		
		foreach ($contents as $key => $value) {

			$bgcolor = (($key % 2) == 1 ? 'bgcolor="#ddebf7"' : '');

			$date = new \DateTime($value['create_time']);

			$objetos = $as_objetos->get(array('id_as' => $value['id_as'] ));

			$projeto_list .= '<tr>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$date->format('d/m/Y H:i').'</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$value['as_projeto_code'].'</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$value['as_projeto_nome'].'</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center"> ';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$projeto_list .= '<p>'.$objeto['objeto_origem_cidade'].'-'.$objeto['objeto_origem_uf'].'</p>';
					}

				$projeto_list .= '</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center"> ';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$projeto_list .= '<p>'.$objeto['objeto_destino_cidade'].'-'.$objeto['objeto_destino_uf'].'</p>';
					}

				$projeto_list .= '</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;" align="center">';

					foreach ($objetos['results'] as $obj_key => $objeto) {
						$projeto_list .= '<p>'.
							$objeto['objeto_comprimento'].'x'.
							$objeto['objeto_largura'].'x'.
							$objeto['objeto_altura'].'<br/>'.
							$objeto['objeto_peso_unit'].
							'</p>';
					}

				$projeto_list .= '</td>';				
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;">'.$value['cat_name'].'</td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;"></td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;"></td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;"></td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;"></td>';
				$projeto_list .= '<td '.$bgcolor.' style="padding: 4px 5px;"></td>';
			$projeto_list .= '</tr>';
		}


		$content = Utilities::file_reader($this->relatorios_template_path.'as-projeto.html',array(
			'|*PROJETOS_LIST*|' => $projeto_list,
			'|*EMISSAO*|' => date('d/m/Y H:i'),
			'|*PROJETO*|' => $value['as_projeto_code'],
		));

		$mpdf->WriteHTML($content);

		$mpdf->shrink_tables_to_fit = 1;

		// Salva o arquivo
		$dir = $this->get_directory();

		if($dir['result']){

			$filename = 'projeto-'.date('d-m-Y-H-i-s').'.pdf';
			$dir_file = $dir['path'].$filename;

			$mpdf->Output($dir_file);

			$response['file'] = $dir['uri'].$filename;
			$response['result'] = true;

		}else{
			$response['error'] = $dir['error'];
		}		

		return $response;

	}
	


}

?>