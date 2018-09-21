<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\UploadedFile;
use Classes\Upload;
use Classes\Utilities;


// Upload de arquivo

$app->post('/upload', function (Request $request, Response $response, array $args) {

	$data = array(
		'result' => false
	);

	$upload = new Upload($this->db);

	$directory = $upload->get_directory();

	if($directory['result']){

	    $uploadedFiles = $request->getUploadedFiles();

	    if ($uploadedFiles['file']->getError() === UPLOAD_ERR_OK) {
	        $data = $upload->moveUploadedFile($directory, $uploadedFiles['file']);
	    }

	}else{
		$data = $directory;
	}

	return $response->withJson($data);
});


?>