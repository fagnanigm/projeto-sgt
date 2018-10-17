<?php

namespace Classes;

class ACBr {

	private $ftp_server = '192.168.1.110';
	private $ftp_port = '8801';
	private $ftp_username = 'sgt_user';
	private $ftp_password = 'fagnani33';

	private $ftp_conn = false;

	public function connect(){

		$response = array(
			'result' => false
		);

		try {

			$this->ftp_conn = ftp_connect($this->ftp_server, $this->ftp_port);
			if (false === $this->ftp_conn) {
			    $response['error'] = 'Servidor de FTP ACBr indisponível.';
			    return $response;
			}

			$loggedIn = ftp_login($this->ftp_conn,  $this->ftp_username,  $this->ftp_password);
			if (false === $loggedIn) {
			    $response['error'] = 'Erro ao se autenticar com o FTP ACBr.';
			    return $response;
			}

			$response['result'] = true;
			$response['ftp_conn'] = $this->ftp_conn;			

		} catch (Exception $e) {
			$response['error'] = "Failure: " . $e->getMessage();
		}

		return $response;

	}

	public function input($origem, $destino){

		$response = array(
			'result' => false
		);

		$destino = '/Entrada/'.$destino;

		if (ftp_put($this->ftp_conn, $destino,  $origem, FTP_ASCII)){
			$response['result'] = true;
		}else{
			$response['error'] = "Falha ao carregar arquivo.";
		}

		return $response;

	}

	public function get($file, $destino){

		$response = array(
			'result' => false
		);
		
		$server_file = $this->file_name_response_parser($file);
		$local_file = $destino.$server_file;

		$attemp = 0;

		while(ftp_get($this->ftp_conn, $local_file, '/Saida/'.$server_file, FTP_ASCII) == false){

			$attemp++;
			if(file_exists($local_file)){
				break;
			}else{
				if($attemp <= 10){
					sleep(1);
				}else{
					break;
				}
			}

		}

		$response['result'] = file_exists($local_file);
		$response['attemp'] = $attemp;
		$response['file'] = $local_file;

		return $response;

	}


	public function disconnect(){
		ftp_close($this->ftp_conn); 
	}

	public function file_name_response_parser($file){

		$file = str_replace(array('.ini'), array('-resp.ini'),$file);

		return $file;

	}


}

?>