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

// CLIENTES
require 'routes/clientes/clientes.php';

// PRODUTOS
require 'routes/produtos/produtos.php';

// LOCAIS
require 'routes/locais/locais.php';

// VEÍCULOS
require 'routes/veiculos/veiculos.php';

// MOTORISTAS
require 'routes/motoristas/motoristas.php';

// PROJETOS
require 'routes/projetos/projetos.php';

