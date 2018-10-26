<?php

namespace Classes;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Relatorios {

	private $db;

	public $base = UPLOAD_PATH;

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

}

?>