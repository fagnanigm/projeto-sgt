<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Classes\Manifestos;

// Emissão de manifesto (MDF-e)
$app->post('/manifestos/emitir', function (Request $request, Response $response, array $args) {
	$manifesto = new Manifestos($this->db);
	$data = $manifesto->emitir($request->getParams());
	return $response->withJson($data);
});

?>