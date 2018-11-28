<?php

namespace Classes;

class Conhecimentos {

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

						$acbr_response = file_get_contents($get_file['file']);

						$response['acbr_response'] = $acbr_response;

						// Gera o arquivo
						$mpdf = new \Mpdf\Mpdf();

						// Coleta o conteúdo

						$content = Utilities::file_reader($this->impressoes_template_path.'conhecimento.html',array(
							'|*CONTENT*|' => $acbr_response
						));

						$mpdf->WriteHTML(utf8_encode($content));

						$mpdf->shrink_tables_to_fit = 1;

						// Salva o arquivo
						$dir = $this->get_directory($as['as_projeto_code_sequencial']);

						if($dir['result']){

							$filename = 'Conhecimento-'.date('d-m-Y-H-i-s').'.pdf';
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

		$ini_content = 'CTE.CRIARENVIARCTE("'.PHP_EOL;

		## Config
		$ini_content .= '[infCte]'.PHP_EOL;
		$ini_content .= 'Versao=3.0'.PHP_EOL;
		$ini_content .= 'Id=41'.PHP_EOL;

		$ini_content .= PHP_EOL;

		## Identificação do CTE
		$ini_content .= '[ide]'.PHP_EOL;

		// Fixos
		$ini_content .= 'cUF=35'.PHP_EOL;
		$ini_content .= 'cCT=84694796'.PHP_EOL;
		$ini_content .= 'mod=57'.PHP_EOL;
		$ini_content .= 'serie=0'.PHP_EOL;
		$ini_content .= 'tplmp=1'.PHP_EOL;
		$ini_content .= 'tpEmis=1'.PHP_EOL;
		$ini_content .= 'cDV=1'.PHP_EOL;
		$ini_content .= 'tpAmb=2'.PHP_EOL;
		$ini_content .= 'tpCTe=0'.PHP_EOL;
		$ini_content .= 'verproc=000000001'.PHP_EOL;
		$ini_content .= 'indGlobalizado=0'.PHP_EOL;
		$ini_content .= 'modal=01'.PHP_EOL;
		$ini_content .= 'tpServ=0'.PHP_EOL;
		$ini_content .= 'indIEToma=1'.PHP_EOL;
		$ini_content .= 'cMunEnv=3501608'.PHP_EOL;
		$ini_content .= 'xMunEnv=AMERICANA'.PHP_EOL;                              
		$ini_content .= 'UFEnv=SP'.PHP_EOL;
		$ini_content .= 'nCT='.$as['as_projeto_code_sequencial'].PHP_EOL;
		$ini_content .= 'dhEmi='.$dt->format('d/m/Y').PHP_EOL;

		// Variável 
		$ini_content .= 'CFOP=5353'.PHP_EOL;
		$ini_content .= 'natOp=1'.PHP_EOL;

		$ini_content .= 'cMunini='.$as['local_coleta_data']['local_cidade_ibge'].PHP_EOL;
		$ini_content .= 'xMunini='.$as['local_coleta_data']['local_cidade'].PHP_EOL; 
		$ini_content .= 'UFini='.$as['local_coleta_data']['local_estado'].PHP_EOL;

		$ini_content .= 'cMunFim='.$as['local_entrega_data']['local_cidade_ibge'].PHP_EOL;
		$ini_content .= 'xMunFim='.$as['local_entrega_data']['local_cidade'].PHP_EOL;
		$ini_content .= 'UFFim='.$as['local_entrega_data']['local_estado'].PHP_EOL; 

		$ini_content .= PHP_EOL;

		## Tomador
		$ini_content .= '[toma4]'.PHP_EOL;
		$ini_content .= 'toma=4'.PHP_EOL;
		$ini_content .= 'CNPJ='.$_POST['cte']['informacoes_gerais']['tomador']['cnpj'].PHP_EOL;
		$ini_content .= 'IE='.$_POST['cte']['informacoes_gerais']['tomador']['ie'].PHP_EOL;
		//$ini_content .= 'xNome=B-PROJECTS TRANSPORTES NACIONAIS E INTERN.LTDA.'.PHP_EOL;
		//$ini_content .= 'xFant=B-PROJECTS TRANSPORTES NACIONAIS E INTERN.LTDA.'.PHP_EOL;

		$ini_content .= PHP_EOL;

		$ini_content .= '[enderToma]'.PHP_EOL;
		$ini_content .= 'xLgr='.$_POST['cte']['informacoes_gerais']['tomador']['logradouro'].PHP_EOL;
		$ini_content .= 'nro='.$_POST['cte']['informacoes_gerais']['tomador']['numero'].PHP_EOL;
		$ini_content .= 'xBairro='.$_POST['cte']['informacoes_gerais']['tomador']['bairro'].PHP_EOL;
		$ini_content .= 'cMun='.$_POST['cte']['informacoes_gerais']['tomador']['codigo_cidade'].PHP_EOL;
		$ini_content .= 'xMun='.$_POST['cte']['informacoes_gerais']['tomador']['cidade'].PHP_EOL;
		$ini_content .= 'CEP='.$_POST['cte']['informacoes_gerais']['tomador']['cep'].PHP_EOL;
		$ini_content .= 'UF='.$_POST['cte']['informacoes_gerais']['tomador']['estado'].PHP_EOL;
		$ini_content .= 'cPais='.$_POST['cte']['informacoes_gerais']['tomador']['codigo_pais'].PHP_EOL;
		$ini_content .= 'xPais='.$_POST['cte']['informacoes_gerais']['tomador']['pais'].PHP_EOL;

		$ini_content .= PHP_EOL;

		## Emitente - Fixo
		$ini_content .= '[emit]'.PHP_EOL;
		$ini_content .= 'IE=110817205116'.PHP_EOL;
		$ini_content .= 'CNPJ=47698881000120'.PHP_EOL;
		$ini_content .= 'xNome=TRANSPORTADORA CRUZ DE MALTA LTDA'.PHP_EOL;
		$ini_content .= 'xFant=TRANSPORTADORA CRUZ DE MALTA LTDA'.PHP_EOL;
		$ini_content .= 'xLgr=Rua do Emitente'.PHP_EOL;
		$ini_content .= 'nro=31'.PHP_EOL;
		$ini_content .= 'xCpl=Complemento'.PHP_EOL;
		$ini_content .= 'xBairro=PARQUE NOVO MUNDO'.PHP_EOL;
		$ini_content .= 'cMun=3501608'.PHP_EOL;
		$ini_content .= 'xMun=AMERICANA'.PHP_EOL;
		$ini_content .= 'CEP=13467444'.PHP_EOL;
		$ini_content .= 'UF=SP'.PHP_EOL;
		$ini_content .= 'fone=34051234'.PHP_EOL;

		$ini_content .= PHP_EOL;


		## Remetente
		$ini_content .= '[rem]'.PHP_EOL;
		$ini_content .= 'CNPJCPF='.$_POST['cte']['informacoes_gerais']['remetente']['cnpj'].PHP_EOL;
		$ini_content .= 'IE='.$_POST['cte']['informacoes_gerais']['remetente']['ie'].PHP_EOL;
		$ini_content .= 'xNome='.$_POST['cte']['informacoes_gerais']['remetente']['nome'].PHP_EOL;
		$ini_content .= 'xFant='.$_POST['cte']['informacoes_gerais']['remetente']['nome'].PHP_EOL;
		$ini_content .= 'fone=993115474'.PHP_EOL;
		$ini_content .= 'xLgr='.$_POST['cte']['informacoes_gerais']['remetente']['logradouro'].PHP_EOL;
		$ini_content .= 'nro='.$_POST['cte']['informacoes_gerais']['remetente']['numero'].PHP_EOL;
		$ini_content .= 'xCpl='.PHP_EOL;
		$ini_content .= 'xBairro='.$_POST['cte']['informacoes_gerais']['remetente']['bairro'].PHP_EOL;
		$ini_content .= 'cMun='.$_POST['cte']['informacoes_gerais']['remetente']['codigo_cidade'].PHP_EOL;
		$ini_content .= 'xMun='.$_POST['cte']['informacoes_gerais']['remetente']['cidade'].PHP_EOL;
		$ini_content .= 'CEP='.$_POST['cte']['informacoes_gerais']['remetente']['cep'].PHP_EOL;
		$ini_content .= 'UF='.$_POST['cte']['informacoes_gerais']['remetente']['estado'].PHP_EOL;
		$ini_content .= 'PaisCod='.$_POST['cte']['informacoes_gerais']['remetente']['codigo_pais'].PHP_EOL;
		$ini_content .= 'Pais='.$_POST['cte']['informacoes_gerais']['remetente']['pais'].PHP_EOL;
		$ini_content .= 'Email=fagnanigm@gmail.com'.PHP_EOL;

		$ini_content .= PHP_EOL;

		## Destinatário 
		$ini_content .= '[Dest]'.PHP_EOL;
		$ini_content .= 'CNPJCPF='.$_POST['cte']['informacoes_gerais']['destinatario']['cnpj'].PHP_EOL;
		$ini_content .= 'xNome='.$_POST['cte']['informacoes_gerais']['destinatario']['nome'].PHP_EOL;
		$ini_content .= 'fone='.PHP_EOL;
		$ini_content .= 'xLgr='.$_POST['cte']['informacoes_gerais']['destinatario']['logradouro'].PHP_EOL;
		$ini_content .= 'nro='.$_POST['cte']['informacoes_gerais']['destinatario']['numero'].PHP_EOL;
		$ini_content .= 'xCpl='.PHP_EOL;
		$ini_content .= 'xBairro='.$_POST['cte']['informacoes_gerais']['destinatario']['bairro'].PHP_EOL;
		$ini_content .= 'cMun='.$_POST['cte']['informacoes_gerais']['destinatario']['codigo_cidade'].PHP_EOL;
		$ini_content .= 'xMun='.$_POST['cte']['informacoes_gerais']['destinatario']['cidade'].PHP_EOL;
		$ini_content .= 'CEP='.$_POST['cte']['informacoes_gerais']['destinatario']['cep'].PHP_EOL;
		$ini_content .= 'UF='.$_POST['cte']['informacoes_gerais']['destinatario']['estado'].PHP_EOL;
		$ini_content .= 'cPais='.$_POST['cte']['informacoes_gerais']['destinatario']['codigo_pais'].PHP_EOL;
		$ini_content .= 'xPais='.$_POST['cte']['informacoes_gerais']['destinatario']['pais'].PHP_EOL;

		$ini_content .= PHP_EOL;


		## Carga
		$ini_content .= '[infCTeNorm]'.PHP_EOL;

		$ini_content .= PHP_EOL;

		// Variavel
		$ini_content .= '[infCarga]'.PHP_EOL;
		$ini_content .= 'vCarga=1000,00'.PHP_EOL;
		$ini_content .= 'proPred=Produto de teste'.PHP_EOL;
		$ini_content .= 'xOutCat=Carga viva'.PHP_EOL;

		$ini_content .= PHP_EOL;

		// Varivel
		$ini_content .= '[infQ001]'.PHP_EOL;
		$ini_content .= 'cUnid=KG'.PHP_EOL;
		$ini_content .= 'tpMed=PESO BRUTO'.PHP_EOL;
		$ini_content .= 'qCarga=100'.PHP_EOL;

		$ini_content .= PHP_EOL;

		// Variavel
		$ini_content .= '[rodo]'.PHP_EOL;
		$ini_content .= 'RNTRC=12345678'.PHP_EOL;

		$ini_content .= PHP_EOL;

		## Documentos
		$ini_content .= '[infDoc]'.PHP_EOL;

		$ini_content .= PHP_EOL;

		$ini_content .= '[infNFe001]'.PHP_EOL;
		$ini_content .= 'chave=35140289237911011690550000000093571642024852'.PHP_EOL;
		$ini_content .= 'PIN='.PHP_EOL;
		$ini_content .= 'dPrev='.PHP_EOL;

		$ini_content .= '")';

		return $ini_content;


	}

}

?>