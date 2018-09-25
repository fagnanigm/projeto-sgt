<?php

namespace Classes;

use Slim\Http\UploadedFile;

class Upload {

	private $db;

	public $base = UPLOAD_PATH;

	function __construct($db = false){
		if(!$db){
			die();
		}
		$this->db = $db;
	}

	public function file_name_format($filename){
		$filename = substr($filename, 0, strrpos($filename, "."));
		$filename = Utilities::slugify($filename);
		return $filename;
	}
	
	public function get_directory(){

		$response = array(
			'result' => false
		);

		$uri = '/uploads/'.date('Y').'/'.date('m').'/';

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

	public function moveUploadedFile($directory, UploadedFile $uploadedFile){

		$response = array(
			'result' => false
		);

	    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
	    $basename = $this->file_name_format($uploadedFile->getClientFilename());
	    $filename = sprintf('%s.%0.8s', $basename, $extension);

	    $num = 1;

	    while(file_exists($directory['path'] . $filename)){

	    	$basename = $this->file_name_format($uploadedFile->getClientFilename()) . '-' . $num;
	    	$filename = sprintf('%s.%0.8s', $basename, $extension);
	    	$num++;

	    }
	    
	    $uploadedFile->moveTo($directory['path'] . $filename);

	    $date = new \DateTime();	

	    $data = array(
	    	'file_name' => $filename,
	    	'file_path' => $directory['uri'] . $filename,
	    	'create_time' => $date->format("Y-m-d\TH:i:s")
	    );

	    $insertStatement = $this->db->insert(array_keys($data))->into('arquivos')->values(array_values($data));
		$data['id'] = $insertStatement->execute();

		if(strlen($data['id']) > 0){

			$data['create_timestamp'] = $date->getTimestamp();

			$response['result'] = true;
			$response['data'] = $data;
		}

	    return $response;
	}

}

?>