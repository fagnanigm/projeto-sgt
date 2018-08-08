<?php

// use Slim\Http\Request;
// use Slim\Http\Response;

// Routes

 
$app->get('/[{name}]', function ($request, $response, array $args) {

    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});


// USERS 
require 'routes/users/users.php';

// EMPRESAS
require 'routes/empresas/empresas.php';

// CONFIGURAÇÕES
require 'routes/configuracoes/configuracoes.php';