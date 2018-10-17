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

// CATEGORIAS
require 'routes/categorias/categorias.php';

// LOCAIS
require 'routes/locais/locais.php';

// VEÍCULOS
require 'routes/veiculos/veiculos.php';
require 'routes/veiculos/tipos.php';

// MOTORISTAS
require 'routes/motoristas/motoristas.php';

// PROJETOS
require 'routes/projetos/projetos.php';

// LOCALIDADES
require 'routes/localidades/localidades.php';

// COTACOES
require 'routes/cotacoes/cotacoes.php';

// AUTORIZACAO SERVICO
require 'routes/autorizacao-servico/autorizacao-servico.php';

// VENDEDORES
require 'routes/vendedores/vendedores.php';

// FORMAS PAGAMENTO
require 'routes/formas-pagamento/formas-pagamento.php';

// UPLOAD
require 'routes/upload/upload.php';

// Autorizacao servico prazo pagamento
require 'routes/autorizacao-servico-prazo-pg/autorizacao-servico-prazo-pg.php';

// VALIDADES PROPOSTA
require 'routes/validades-proposta/validades-proposta.php';

// PRAZO RAZÕES
require 'routes/prazo-razoes/prazo-razoes.php';

// TAXAS LICENÇAS
require 'routes/taxas-licencas/taxas-licencas.php';
require 'routes/taxas-licencas/taxas-licencas-tipos.php';
require 'routes/taxas-licencas/taxas-licencas-categorias.php';

// CFOP
require 'routes/cfop/cfop.php';

// Conhecimentos
require 'routes/conhecimentos/conhecimentos.php';