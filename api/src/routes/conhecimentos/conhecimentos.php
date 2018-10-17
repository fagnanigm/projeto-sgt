<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Conhecimentos;

// Emissão de conhecimento (CT-e)
$app->post('/conhecimentos/emitir', function (Request $request, Response $response, array $args) {
	$cte = new Conhecimentos($this->db);
	$data = $cte->emitir($request->getParams());
	return $response->withJson($data);
});


?>