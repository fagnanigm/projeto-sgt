<?php

namespace Classes;

class Manifestos {

	private $db;

	public $base = UPLOAD_PATH;
	public $impressoes_template_path = '../vendor/impressoes-templates/';

	function __construct($db = false){
		if(!$db){
			die();
		}
		$this->db = $db;
	}

	public function emitir($args = array()){

		$response = array(
			'result' => false,
		);

		if(!isset($args['id_as'])){
			$response['error'] = 'O campo id_as é obrigatório.';
			return $response;
		}else{

			$as_resources = new AutorizacaoServico($this->db);
			$as = $as_resources->get_by_id($args['id_as']);

			if(!$as['result']){
				$response['error'] = 'AS não encontrada.';
				return $response;
			}else{
				$as = $as['as'];
			}

		}

		// Monta conteúdo
		$content = $this->get_content($as);

		// Cria diretorio
		$dir_resources = $this->get_directory($as['as_projeto_code_sequencial']);

		if(!$dir_resources['result']){
			$response['error'] = $dir_resources['error'];
			return $response;
		}

		// Cria arquivos
		$path = $dir_resources['path'];
		$filename = $as['as_projeto_code'].'-'.time().'.ini';
		$file = $path.$filename;


		$fileObj = fopen($file, 'w');
		fwrite($fileObj,$content);

		if(fclose($fileObj)){

			// Coloca arquivo gerado no servidor da NEC 192.168.1.110:8801

			$acbr = new ACBr();
		
			$ftp_conn = $acbr->connect();

			if($ftp_conn['result']){

				$input_file = $acbr->input($file, $filename);

				if($input_file['result']){

					$get_file = $acbr->get($filename, $path);

					if($get_file['result']){

						$acbr_response = utf8_encode(file_get_contents($get_file['file']));

						$response['acbr_response'] = $acbr_response;

						// Gera o arquivo
						$mpdf = new \Mpdf\Mpdf();

						// Coleta o conteúdo

						$content = Utilities::file_reader($this->impressoes_template_path.'manifesto.html',array(
							'|*CONTENT*|' => $acbr_response
						));

						$mpdf->WriteHTML($content);

						$response['content'] = $content;

						$mpdf->shrink_tables_to_fit = 1;

						// Salva o arquivo
						$dir = $this->get_directory($as['as_projeto_code_sequencial']);

						if($dir['result']){

							$filename = 'Manifesto-'.date('d-m-Y-H-i-s').'.pdf';
							$dir_file = $dir['path'].$filename;

							$mpdf->Output($dir_file);

							$response['file'] = $dir['uri'].$filename;
							$response['result'] = true;

						}else{
							$response['error'] = $dir['error'];
						}

					}else{
						$response['error'] = 'Erro ao baixar resposta do ACBr.';
						return $response;
					}

				}else{
					$response['error'] = $input_file['error'];
					return $response;
				}

				$acbr->disconnect();

			}else{
				$response['error'] = $ftp_conn['error'];
				return $response;
			}

		}		

		$response['path'] = $path;
		$response['as'] = $as;

		return $response;

	}


	public function get_directory($as_num){

		$response = array(
			'result' => false
		);

		$uri = '/conhecimentos/'.date('Y').'/'.date('m').'/'.$as_num.'/';

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

	public function get_content($as){

		$dt = new \DateTime();

		$ini_content = 'MDFe.CriarEnviarMDFe("'.PHP_EOL;

		$ini_content .= '[ide]';

		$ini_content .= 'cUF=35';
		$ini_content .= 'tpAmb=2';
		$ini_content .= 'tpEmit=1';
		$ini_content .= 'mod=58';
		$ini_content .= 'serie=1';
		$ini_content .= 'nMDF=00017';
		$ini_content .= 'cMDF=';
		$ini_content .= 'modal=1';
		$ini_content .= 'dhemi=06/02/2017 10:21:14';
		$ini_content .= 'tpEmis=1';
		$ini_content .= 'procEmi=0';
		$ini_content .= 'verProc=1';
		$ini_content .= 'UFIni=SE';
		$ini_content .= 'UFFim=SP';
		$ini_content .= 'tpTransp=0';

		$ini_content .= '[perc001]';
		$ini_content .= 'UFPer=BA';

		$ini_content .= '[perc002]';
		$ini_content .= 'UFPer=MG';

		$ini_content .= '[CARR001]';
		$ini_content .= 'cMunCarrega=3534401';
		$ini_content .= 'xMunCarrega=OSASCO';                  
		$ini_content .= 'dhIniViagem=04/02/2017';

		$ini_content .= '[emit]';
		$ini_content .= 'CNPJ=47698881000120';
		$ini_content .= 'IE=110817205116';
		$ini_content .= 'Xnome=DJ SYSTEM';
		$ini_content .= 'XFant=DJ SYSTEM';
		$ini_content .= 'XLgr=Endereco Emitente';
		$ini_content .= 'nro=200';
		$ini_content .= 'XCpl=';
		$ini_content .= 'XBairro=Centro';
		$ini_content .= 'cMun=3548500';
		$ini_content .= 'xMun=SANTOS';
		$ini_content .= 'CEP=18280000';
		$ini_content .= 'UF=SP';
		$ini_content .= 'fone=';

		$ini_content .= '[Rodo]';
		$ini_content .= 'codAgPorto=';

		// Informe se existir informações RNTRC

		$ini_content .= '[infANTT]';
		$ini_content .= 'RNTRC=22222222';

		// Informe se existir informações CIOT

		$ini_content .= '[infCIOT001]';
		$ini_content .= 'CIOT=121212121212';
		$ini_content .= 'CNPJCPF=99999999999999';

		// Informe se existir informações Vale Pedágio

		$ini_content .= '[valePed001]';
		$ini_content .= 'CNPJForn=';
		$ini_content .= 'CNPJPg=';
		$ini_content .= 'nCompra=';
		$ini_content .= 'vValePed=';

		// Informe se existir Contratante

		$ini_content .= '[infContratante001]';
		$ini_content .= 'CNPJCPF=';
			
		$ini_content .= '[veicTracao]';
		$ini_content .= 'cInt=1';
		$ini_content .= 'placa=AAA1234';
		$ini_content .= 'UF=SP';
		$ini_content .= 'RENAVAM=45642656266';
		$ini_content .= 'tara=';
		$ini_content .= 'capKG=';
		$ini_content .= 'capM3=';
		$ini_content .= 'CNPJCPF=99999999999';
		$ini_content .= 'RNTRC=22222222';
		$ini_content .= 'xNome=nome do proprietario';
		$ini_content .= 'IE=';
		$ini_content .= 'UFProp=';
		$ini_content .= 'tpProp=';
		$ini_content .= 'tpRod=';
		$ini_content .= 'tpCar=';
		$ini_content .= 'UF=SP';

		$ini_content .= '[moto001]';
		$ini_content .= 'xNome=nome do motorista';
		$ini_content .= 'CPF=99999999999';

		$ini_content .= '[reboque001]';
		$ini_content .= 'cInt=1';
		$ini_content .= 'placa=AAA1234';
		$ini_content .= 'RENAVAM=45642656266';
		$ini_content .= 'tara=';
		$ini_content .= 'capKG=';
		$ini_content .= 'capM3=';
		$ini_content .= 'UF=SP';
		$ini_content .= 'CNPJCPF=99999999999';
		$ini_content .= 'RNTRC=22222222';
		$ini_content .= 'xNome=nome do proprietario';
		$ini_content .= 'IE=';
		$ini_content .= 'UFProp=';
		$ini_content .= 'tpProp=';
		$ini_content .= 'tpCar=';
		$ini_content .= 'UF=';

		$ini_content .= '[DESC001]';
		$ini_content .= 'cMunDescarga=3518701';
		$ini_content .= 'xMunDescarga=GUARUJA ';

		// Utilize tags abaixo para Adicionar CTes Relacionados

		$ini_content .= '[infCTe001001]';
		$ini_content .= 'chCTe=';
		$ini_content .= 'SegCodBarra=';
		$ini_content .= 'indReentrega=';

		$ini_content .= '[peri001001001]';
		$ini_content .= 'nONU=';
		$ini_content .= 'xNomeAE=';
		$ini_content .= 'xClaRisco=';
		$ini_content .= 'grEmb=';
		$ini_content .= 'qTotProd=';
		$ini_content .= 'qVolTipo=';

		$ini_content .= '[infUnidTransp001001001]';
		$ini_content .= 'idUnidTransp=';
		$ini_content .= 'tpUnidTransp=';
		$ini_content .= 'qtdRat=';

		$ini_content .= '[lacUnidTransp001001001001]';
		$ini_content .= 'nLacre=';

		$ini_content .= '[infUnidCarga001001001001]';
		$ini_content .= 'idUnidCarga=';
		$ini_content .= 'tpUnidCarga';
		$ini_content .= 'qtdRat=';

		$ini_content .= '[lacUnidCarga001001001001001]';
		$ini_content .= 'nLacre=';

			// Utilize tags abaixo para Adicionar NFes Relacionadas

		$ini_content .= '[infNFe001001]';
		$ini_content .= 'chNFe=';
		$ini_content .= 'SegCodBarra=';
		$ini_content .= 'indReentrega=';

		$ini_content .= '[peri001001001]';
		$ini_content .= 'nONU=';
		$ini_content .= 'xNomeAE=';
		$ini_content .= 'xClaRisco=';
		$ini_content .= 'grEmb= ';   
		$ini_content .= 'qTotProd=';
		$ini_content .= 'qVolTipo=';

		$ini_content .= '[infUnidTransp001001001]';
		$ini_content .= 'idUnidTransp=';
		$ini_content .= 'tpUnidTransp=';
		$ini_content .= 'qtdRat=';

		$ini_content .= '[lacUnidTransp001001001001]';
		$ini_content .= 'nLacre=';

		$ini_content .= '[infUnidCarga001001001001]';
		$ini_content .= 'idUnidCarga=';
		$ini_content .= 'tpUnidCarga';
		$ini_content .= 'qtdRat=';

		$ini_content .= '[lacUnidCarga001001001001001]';
		$ini_content .= 'nLacre=';

			// Utilize tags abaixo para Adicionar MDFes Relacionados

		$ini_content .= '[infMDFeTransp001001]';
		$ini_content .= 'chMDFe=';
		$ini_content .= 'indReentrega=';

		$ini_content .= '[peri001001001]';
		$ini_content .= 'nONU=    ';
		$ini_content .= 'xNomeAE=  ';
		$ini_content .= 'xClaRisco=';
		$ini_content .= 'grEmb=  ';  
		
		$ini_content .= 'qTotProd=';  
		$ini_content .= 'qVolTipo=';  

		$ini_content .= '[infUnidTransp001001001]';
		$ini_content .= 'idUnidTransp=';
		$ini_content .= 'tpUnidTransp=';
		$ini_content .= 'qtdRat=';

		$ini_content .= '[lacUnidTransp001001001001]';
		$ini_content .= 'nLacre=';

		$ini_content .= '[infUnidCarga001001001001]';
		$ini_content .= 'idUnidCarga=';
		$ini_content .= 'tpUnidCarga';
		$ini_content .= 'qtdRat=';

		$ini_content .= '[lacUnidCarga001001001001001]';
		$ini_content .= 'nLacre=';

		// Informação para Adicionar dados do Seguro

		$ini_content .= '[seg001]';
		$ini_content .= 'respSeg=1';
		$ini_content .= 'CNPJCPF=99999999999999';
		$ini_content .= 'xSeg=xxxxxxxxxxxxxx  ';
		$ini_content .= 'CNPJ=99999999999999  ';
		$ini_content .= 'nApol=544534';

		$ini_content .= '[aver001001]';
		$ini_content .= 'nAver=521451';

		$ini_content .= '[tot]';
		$ini_content .= 'qCTe=1';
		$ini_content .= 'qNFe=0';
		$ini_content .= 'vCarga=57488.92';
		$ini_content .= 'cUnid=01';
		$ini_content .= 'qCarga=6877.00';

		// Informação para número de Lacre

		$ini_content .= '[lacres001]';
		$ini_content .= 'nLacre=HGW85173';

		// Informações complementares para uso Fisco

		$ini_content .= '[infAdic]';
		$ini_content .= 'infCpl=';
		$ini_content .= 'infAdFisco=';

		$ini_content .= '",1)';

		return $ini_content;


	}

}

?>