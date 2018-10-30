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
		$mpdf = new \Mpdf\Mpdf();

		// Coleta o conteúdo

		$content = Utilities::file_reader($this->impressoes_template_path.'cotacoes.html',array(
			'|*CONTENT*|' => '<pre>'.print_r($cotacao, true).'</pre>'
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

	public function print_as($args){

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

		$content = Utilities::file_reader($this->impressoes_template_path.'autorizacao-servico.html',array(
			'|*CONTENT*|' => '<pre>'.print_r($as, true).'</pre>'
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