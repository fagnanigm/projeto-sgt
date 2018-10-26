<?php

// use Slim\Http\Request;
// use Slim\Http\Response;

// Routes

/**
 * @SWG\Swagger(
 *     schemes={"http"},
 	   @SWG\SecurityScheme(
 *         securityDefinition="Bearer",
 *         type="apiKey",
 *         name="Authorization",
 *         in="header"
 *     ),
 *     host="200.160.111.85:9090",
 *     basePath="/api/public/",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="SGT - Sistema de Gestão de Transporte",
 *         description="O sistema SGT – Sistema de gestão de transporte – é um sistema desenvolvido pela a empresa NEC Brasil, tendo a sua primeira versão lançada no ano de 2018. O projeto consiste na gestão de empresas do ramo de logísticas e que atuam no mercado de transportes de cargas de médio e grande porte. 
O objetivo principal do sistema é facilitar a emissão de documentos fiscais necessários para a circulação da carga, sendo eles: CT-e e MDF-e. Para emitir esses documentos, é utilizado uma ferramenta intermediária ACBr, que tem como principal função de processar os documentos no SEFAZ. Esse software está integrado com o sistema da OMIE para questões financeiras.
Esta API faz a integração de todo o sistema, podendo ser utilizado por qualquer tipo de plataforma, desde requisições em javascript, aplicativos mobile e outros. Todos os métodos seguem um mesmo padrão de inserção, seleção, atualização e remoção. Veja abaixo todas as funções dessa API.
",
 *         @SWG\Contact(
 *             email="guilherme.fagnani@necbrasil.com.br"
 *         )
 *     ),
 *     
 * )
 */

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

// CONHECIMENTOS
require 'routes/conhecimentos/conhecimentos.php';

// EQUIPAMENTOS TIPOS COMERCIAIS
require 'routes/equipamentos-tipos-comerciais/equipamentos-tipos-comerciais.php';

// TEXTOS PADRÕES
require 'routes/textos-padroes/textos-padroes.php';

// RELATÓRIOS
require 'routes/relatorios/relatorios.php';

// IMPRESSÕES
require 'routes/impressoes/impressoes.php';