<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {

    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});


// USERS 

$app->get('/users/get', function (Request $request, Response $response, array $args) {

	// $query = $this->db->select()->from('sgt_cte');
	$query = $this->db->query('select * from sgt_cte;');
	$data = $query->fetchAll();

	return $response->withJson($data);

});

$app->post('/users/insert', function (Request $request, Response $response, array $args) {

	var_dump($args);

	return $response->withJson(array(
		'a' => '1'
	));

});