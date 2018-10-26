(function () {
    'use strict';

    angular
        .module('app')
        .config(config);

    function config($stateProvider, $urlRouterProvider, $locationProvider) {
        // default route
        $locationProvider.html5Mode(true);
        $urlRouterProvider.otherwise("/");
        
        // app routes
        $stateProvider
            .state('login', {
                url: '/',
            })

            // EMPRESAS

            .state('select-empresa', {
                url: '/seleciona-empresa',
                templateUrl: 'app/components/empresas/seleciona-empresa.view.html',
                controller: 'Empresas.SelectController',
                controllerAs: 'vm'
            })
            .state('insert-empresa', {
                url: '/cadastrar-empresa',
                templateUrl: 'app/components/empresas/cadastro-empresa.view.html',
                controller: 'Empresas.GerenciaController',
                controllerAs: 'vm'
            })
            .state('update-empresa', {
                url: '/empresas/gerencia/{id_empresa}?queryString',
                templateUrl: 'app/components/empresas/cadastro-empresa.view.html',
                controller: 'Empresas.GerenciaController',
                controllerAs: 'vm'
            })
            .state('visualizar-empresa', {
                url: '/empresas/visualizar/{id_empresa}?queryString',
                templateUrl: 'app/components/empresas/visualizar.view.html',
                controller: 'Empresas.VisualizarController',
                controllerAs: 'vm'
            })
            .state('empresas', {
                url: '/empresas',
                templateUrl: 'app/components/empresas/index.view.html',
                controller: 'Empresas.IndexController',
                controllerAs: 'vm'
            })
            .state('empresas-paged', {
                url: '/empresas/{page}?queryString',
                templateUrl: 'app/components/empresas/index.view.html',
                controller: 'Empresas.IndexController',
                controllerAs: 'vm'
            })

            // DASHBOARD

            .state('dashboard', {
                url: '/dashboard',
                templateUrl: 'app/components/home/index.view.html',
                controller: 'Home.IndexController',
                controllerAs: 'vm'
            })

            // CATEGORIAS 

            .state('categorias', {
                url: '/categorias',
                templateUrl: 'app/components/categorias/index.view.html',
                controller: 'Categorias.IndexController',
                controllerAs: 'vm'
            })
            .state('insert-categoria', {
                url: '/categorias/gerencia',
                templateUrl: 'app/components/categorias/gerencia.view.html',
                controller: 'Categorias.GerenciaController',
                controllerAs: 'vm'
            })
            .state('update-categoria', {
                url: '/categorias/gerencia/{id_categoria}?queryString',
                templateUrl: 'app/components/categorias/gerencia.view.html',
                controller: 'Categorias.GerenciaController',
                controllerAs: 'vm'
            })
            .state('visualizar-categoria', {
                url: '/categorias/visualizar/{id_categoria}?queryString',
                templateUrl: 'app/components/categorias/visualizar.view.html',
                controller: 'Categorias.VisualizarController',
                controllerAs: 'vm'
            })
            .state('categorias-paged', {
                url: '/categorias/{page}?queryString',
                templateUrl: 'app/components/categorias/index.view.html',
                controller: 'Categorias.IndexController',
                controllerAs: 'vm'
            })


            // USUÁRIOS

            .state('usuarios', {
                url: '/usuarios',
                templateUrl: 'app/components/usuarios/index.view.html',
                controller: 'Usuarios.IndexController',
                controllerAs: 'vm'
            })
            
            .state('edit-usuario', {
                url: '/usuarios/gerencia/{id_user}?queryString',
                templateUrl: 'app/components/usuarios/gerencia.view.html',
                controller: 'Usuarios.GerenciaController',
                controllerAs: 'vm'
            })
            .state('insert-usuario', {
                url: '/usuarios/gerencia',
                templateUrl: 'app/components/usuarios/gerencia.view.html',
                controller: 'Usuarios.GerenciaController',
                controllerAs: 'vm'
            })
            .state('importar-usuario', {
                url: '/usuarios/importar-do-omie',
                templateUrl: 'app/components/usuarios/importar.view.html',
                controller: 'Usuarios.GerenciaController',
                controllerAs: 'vm'
            })
            .state('visualizar-usuario', {
                url: '/usuarios/visualizar-usuario',
                templateUrl: 'app/components/usuarios/visualizar.view.html',
                controller: 'Usuarios.VisualizaController',
                controllerAs: 'vm'
            })
            .state('alterar-senha-usuario', {
                url: '/usuarios/alterar-senha',
                templateUrl: 'app/components/usuarios/alterar-senha.view.html',
                controller: 'Usuarios.AlteraSenhaController',
                controllerAs: 'vm'
            })
            .state('usuarios-paged', {
                url: '/usuarios/{page}?queryString',
                templateUrl: 'app/components/usuarios/index.view.html',
                controller: 'Usuarios.IndexController',
                controllerAs: 'vm'
            })

            // CLIENTES

            .state('clientes', {
                url: '/clientes',
                templateUrl: 'app/components/clientes/index.view.html',
                controller: 'Clientes.IndexController',
                controllerAs: 'vm'
            })
            .state('importar-cliente', {
                url: '/clientes/importar-do-omie',
                templateUrl: 'app/components/clientes/importar.view.html',
                controller: 'Clientes.ImportController',
                controllerAs: 'vm'
            })
            .state('visualizar-cliente', {
                url: '/clientes/visualizar/{id_cliente}?queryString',
                templateUrl: 'app/components/clientes/visualizar.view.html',
                controller: 'Clientes.VisualizarController',
                controllerAs: 'vm'
            })
            .state('clientes-paged', {
                url: '/clientes/{page}?queryString',
                templateUrl: 'app/components/clientes/index.view.html',
                controller: 'Clientes.IndexController',
                controllerAs: 'vm'
            })


            // LOCAIS

            .state('locais', {
                url: '/locais',
                templateUrl: 'app/components/locais/index.view.html',
                controller: 'Locais.IndexController',
                controllerAs: 'vm'
            })
            .state('insert-local', {
                url: '/locais/gerencia',
                templateUrl: 'app/components/locais/gerencia.view.html',
                controller: 'Locais.GerenciaController',
                controllerAs: 'vm'
            })
            .state('update-local', {
                url: '/locais/gerencia/{id_local}?queryString',
                templateUrl: 'app/components/locais/gerencia.view.html',
                controller: 'Locais.GerenciaController',
                controllerAs: 'vm'
            })
            .state('visualizar-local', {
                url: '/locais/visualizar/{id_local}?queryString',
                templateUrl: 'app/components/locais/visualizar.view.html',
                controller: 'Locais.VisualizarController',
                controllerAs: 'vm'
            })
            .state('locais-paged', {
                url: '/locais/{page}?queryString',
                templateUrl: 'app/components/locais/index.view.html',
                controller: 'Locais.IndexController',
                controllerAs: 'vm'
            })

            // VEICULOS

            .state('veiculos', {
                url: '/veiculos',
                templateUrl: 'app/components/veiculos/index.view.html',
                controller: 'Veiculos.IndexController',
                controllerAs: 'vm'
            })
            .state('insert-veiculo', {
                url: '/veiculos/gerencia',
                templateUrl: 'app/components/veiculos/gerencia.view.html',
                controller: 'Veiculos.GerenciaController',
                controllerAs: 'vm'
            })

            .state('update-veiculo', {
                url: '/veiculos/gerencia/{id_veiculo}?queryString',
                templateUrl: 'app/components/veiculos/gerencia.view.html',
                controller: 'Veiculos.GerenciaController',
                controllerAs: 'vm'
            })

            .state('visualizar-veiculo', {
                url: '/veiculos/visualizar/{id_veiculo}?queryString',
                templateUrl: 'app/components/veiculos/visualizar.view.html',
                controller: 'Veiculos.VisualizarController',
                controllerAs: 'vm'
            })

            // MOTORISTAS

            .state('motoristas', {
                url: '/motoristas',
                templateUrl: 'app/components/motoristas/index.view.html',
                controller: 'Motoristas.IndexController',
                controllerAs: 'vm'
            })

            .state('insert-motorista', {
                url: '/motoristas/gerencia',
                templateUrl: 'app/components/motoristas/gerencia.view.html',
                controller: 'Motoristas.GerenciaController',
                controllerAs: 'vm'
            })

            .state('update-motorista', {
                url: '/motoristas/gerencia/{id_motorista}?queryString',
                templateUrl: 'app/components/motoristas/gerencia.view.html',
                controller: 'Motoristas.GerenciaController',
                controllerAs: 'vm'
            })

            .state('visualizar-motorista', {
                url: '/motoristas/visualizar/{id_motorista}?queryString',
                templateUrl: 'app/components/motoristas/visualizar.view.html',
                controller: 'Motoristas.VisualizarController',
                controllerAs: 'vm'
            })


            // PROJETOS

            .state('projetos', {
                url: '/projetos',
                templateUrl: 'app/components/projetos/index.view.html',
                controller: 'Projetos.IndexController',
                controllerAs: 'vm'
            })

            .state('visualizar-projeto', {
                url: '/projetos/visualizar/{id_projeto}?queryString',
                templateUrl: 'app/components/projetos/visualizar.view.html',
                controller: 'Projetos.VisualizarController',
                controllerAs: 'vm'
            })

            .state('projetos-paged', {
                url: '/projetos/{page}?queryString',
                templateUrl: 'app/components/projetos/index.view.html',
                controller: 'Projetos.IndexController',
                controllerAs: 'vm'
            })
            
            // AS

            .state('autorizacao-de-servico', {
                url: '/autorizacao-de-servico',
                templateUrl: 'app/components/autorizacao-de-servico/index.view.html',
                controller: 'As.IndexController',
                controllerAs: 'vm'
            })

            .state('insert-autorizacao-de-servico', {
                url: '/autorizacao-de-servico/gerencia',
                templateUrl: 'app/components/autorizacao-de-servico/gerencia.view.html',
                controller: 'As.GerenciaController',
                controllerAs: 'vm'
            })

            .state('update-autorizacao-de-servico', {
                url: '/autorizacao-de-servico/gerencia/{id_as}?queryString',
                templateUrl: 'app/components/autorizacao-de-servico/gerencia.view.html',
                controller: 'As.GerenciaController',
                controllerAs: 'vm'
            })

            .state('visualizar-autorizacao-de-servico', {
                url: '/autorizacao-de-servico/visualizar/{id_as}?queryString',
                templateUrl: 'app/components/autorizacao-de-servico/visualizar.view.html',
                controller: 'As.VisualizarController',
                controllerAs: 'vm'
            })

            .state('autorizacao-de-servico-paged', {
                url: '/autorizacao-de-servico/{page}?queryString',
                templateUrl: 'app/components/autorizacao-de-servico/index.view.html',
                controller: 'As.IndexController',
                controllerAs: 'vm'
            })

            // CTE

            .state('cte-emitidos', {
                url: '/cte-emitidos',
                templateUrl: 'app/components/cte/index.view.html',
                controller: 'Relatorios.IndexController',
                controllerAs: 'vm'
            })

            .state('emitir-cte', {
                url: '/emitir-cte',
                templateUrl: 'app/components/cte/emitir.view.html',
                controller: 'Relatorios.IndexController',
                controllerAs: 'vm'
            })


            // ORDEM DE COLETA

            .state('ordem-de-coleta', {
                url: '/gerar-ordem-de-coleta',
                templateUrl: 'app/components/ordem-de-coleta/gerar.view.html',
                controller: 'Relatorios.IndexController',
                controllerAs: 'vm'
            })


            // ORDEM DE SERVIÇO

            .state('ordem-de-servico', {
                url: '/gerar-ordem-de-servico',
                templateUrl: 'app/components/ordem-de-servico/gerar.view.html',
                controller: 'Relatorios.IndexController',
                controllerAs: 'vm'
            })


            // NOTA FISCAL

            .state('emitir-nota-fiscal', {
                url: '/emitir-nota-fiscal',
                templateUrl: 'app/components/nota-fiscal/emitir.view.html',
                controller: 'Relatorios.IndexController',
                controllerAs: 'vm'
            })


            // OUTROS

            .state('relatorios', {
                url: '/relatorios',
                templateUrl: 'app/components/relatorios/index.view.html',
                controller: 'Relatorios.IndexController',
                controllerAs: 'vm'
            })

            .state('configuracoes', {
                url: '/configuracoes',
                templateUrl: 'app/components/configuracoes/index.view.html',
                controller: 'Configuracoes.IndexController',
                controllerAs: 'vm'
            })


            // COTAÇÕES

            .state('cotacoes', {
                url: '/cotacoes',
                templateUrl: 'app/components/cotacoes/index.view.html',
                controller: 'Cotacoes.IndexController',
                controllerAs: 'vm'
            })
            .state('insert-cotacao', {
                url: '/cotacoes/gerencia',
                templateUrl: 'app/components/cotacoes/gerencia.view.html',
                controller: 'Cotacoes.GerenciaController',
                controllerAs: 'vm'
            })
            .state('update-cotacao', {
                url: '/cotacoes/gerencia/{id_cotacao}?queryString',
                templateUrl: 'app/components/cotacoes/gerencia.view.html',
                controller: 'Cotacoes.GerenciaController',
                controllerAs: 'vm'
            })
            .state('visualizar-cotacao', {
                url: '/cotacoes/visualizar/{id_cotacao}?queryString',
                templateUrl: 'app/components/cotacoes/visualizar.view.html',
                controller: 'Cotacoes.VisualizarController',
                controllerAs: 'vm'
            })
            .state('cotacoes-paged', {
                url: '/cotacoes/{page}?queryString',
                templateUrl: 'app/components/cotacoes/index.view.html',
                controller: 'Cotacoes.IndexController',
                controllerAs: 'vm'
            })


            // VENDEDORES 

            .state('vendedores', {
                url: '/vendedores',
                templateUrl: 'app/components/vendedores/index.view.html',
                controller: 'Vendedores.IndexController',
                controllerAs: 'vm'
            })
            .state('visualizar-vendedor', {
                url: '/vendedores/visualizar/{id_vendedor}?queryString',
                templateUrl: 'app/components/vendedores/visualizar.view.html',
                controller: 'Vendedores.VisualizarController',
                controllerAs: 'vm'
            })
            .state('insert-vendedor', {
                url: '/vendedores/gerencia',
                templateUrl: 'app/components/vendedores/gerencia.view.html',
                controller: 'Vendedores.GerenciaController',
                controllerAs: 'vm'
            })
            .state('update-vendedor', {
                url: '/vendedores/gerencia/{id_vendedor}?queryString',
                templateUrl: 'app/components/vendedores/gerencia.view.html',
                controller: 'Vendedores.GerenciaController',
                controllerAs: 'vm'
            })
            .state('vendedores-paged', {
                url: '/vendedores/{page}?queryString',
                templateUrl: 'app/components/vendedores/index.view.html',
                controller: 'Vendedores.IndexController',
                controllerAs: 'vm'
            })

            // CONFIGURAÇÕES -- GERAL

            // FORMAS DE PAGAMENTO
            .state('formas-de-pagamento', {
                url: '/configuracoes/formas-de-pagamento',
                templateUrl: 'app/components/formas-de-pagamento/index.view.html',
                controller: 'FormasDePagamento.IndexController',
                controllerAs: 'vm'
            })
            .state('insert-forma-de-pagamento', {
                url: '/configuracoes/formas-de-pagamento/gerencia',
                templateUrl: 'app/components/formas-de-pagamento/gerencia.view.html',
                controller: 'FormasDePagamento.GerenciaController',
                controllerAs: 'vm'
            })
            .state('update-forma-de-pagamento', {
                url: '/configuracoes/formas-de-pagamento/gerencia/{id_forma}?queryString',
                templateUrl: 'app/components/formas-de-pagamento/gerencia.view.html',
                controller: 'FormasDePagamento.GerenciaController',
                controllerAs: 'vm'
            })
            .state('formas-de-pagamento-paged', {
                url: '/configuracoes/formas-de-pagamento/{page}?queryString',
                templateUrl: 'app/components/formas-de-pagamento/index.view.html',
                controller: 'FormasDePagamento.IndexController',
                controllerAs: 'vm'
            })


            // VALIDADES DA PROPOSTA
            .state('validades-da-proposta', {
                url: '/configuracoes/validades-da-proposta',
                templateUrl: 'app/components/validades-da-proposta/index.view.html',
                controller: 'ValidadesDaProposta.IndexController',
                controllerAs: 'vm'
            })
            .state('insert-validade-da-proposta', {
                url: '/configuracoes/validades-da-proposta/gerencia',
                templateUrl: 'app/components/validades-da-proposta/gerencia.view.html',
                controller: 'ValidadesDaProposta.GerenciaController',
                controllerAs: 'vm'
            })
            .state('update-validade-da-proposta', {
                url: '/configuracoes/validades-da-proposta/gerencia/{id_validade}?queryString',
                templateUrl: 'app/components/validades-da-proposta/gerencia.view.html',
                controller: 'ValidadesDaProposta.GerenciaController',
                controllerAs: 'vm'
            })
            .state('validades-da-proposta-paged', {
                url: '/configuracoes/validades-da-proposta/{page}?queryString',
                templateUrl: 'app/components/validades-da-proposta/index.view.html',
                controller: 'ValidadesDaProposta.IndexController',
                controllerAs: 'vm'
            })

            // CONFIGURAÇÕES -- VEÍCULOS

            // TIPOS DOS VEÍCULOS
            .state('tipos-dos-veiculos', {
                url: '/configuracoes/tipos-dos-veiculos',
                templateUrl: 'app/components/tipos-dos-veiculos/index.view.html',
                controller: 'TiposDosVeiculos.IndexController',
                controllerAs: 'vm'
            })
            .state('insert-tipos-dos-veiculos', {
                url: '/configuracoes/tipos-dos-veiculos/gerencia',
                templateUrl: 'app/components/tipos-dos-veiculos/gerencia.view.html',
                controller: 'TiposDosVeiculos.GerenciaController',
                controllerAs: 'vm'
            })
            .state('update-tipos-dos-veiculos', {
                url: '/configuracoes/tipos-dos-veiculos/gerencia/{id_tipo}?queryString',
                templateUrl: 'app/components/tipos-dos-veiculos/gerencia.view.html',
                controller: 'TiposDosVeiculos.GerenciaController',
                controllerAs: 'vm'
            })
            .state('tipos-dos-veiculos-paged', {
                url: '/configuracoes/tipos-dos-veiculos/{page}?queryString',
                templateUrl: 'app/components/tipos-dos-veiculos/index.view.html',
                controller: 'TiposDosVeiculos.IndexController',
                controllerAs: 'vm'
            })

            // CONFIGURAÇÕES -- COTAÇÕES

            // EQUIPAMENTOS TIPOS COMERCIAIS
            .state('equipamentos-tipos-comerciais', {
                url: '/configuracoes/equipamentos-tipos-comerciais',
                templateUrl: 'app/components/equipamentos-tipos-comerciais/index.view.html',
                controller: 'EquipTiposComerciais.IndexController',
                controllerAs: 'vm'
            })
            .state('insert-equipamentos-tipos-comerciais', {
                url: '/configuracoes/equipamentos-tipos-comerciais/gerencia',
                templateUrl: 'app/components/equipamentos-tipos-comerciais/gerencia.view.html',
                controller: 'EquipTiposComerciais.GerenciaController',
                controllerAs: 'vm'
            })
            .state('update-equipamentos-tipos-comerciais', {
                url: '/configuracoes/equipamentos-tipos-comerciais/gerencia/{id_tipo}?queryString',
                templateUrl: 'app/components/equipamentos-tipos-comerciais/gerencia.view.html',
                controller: 'EquipTiposComerciais.GerenciaController',
                controllerAs: 'vm'
            })
            .state('equipamentos-tipos-comerciais-paged', {
                url: '/configuracoes/equipamentos-tipos-comerciais/{page}?queryString',
                templateUrl: 'app/components/equipamentos-tipos-comerciais/index.view.html',
                controller: 'EquipTiposComerciais.IndexController',
                controllerAs: 'vm'
            })

            // EQUIPAMENTOS TIPOS COMERCIAIS
            .state('cotacoes-textos-padroes', {
                url: '/configuracoes/cotacoes-textos-padroes',
                templateUrl: 'app/components/cotacoes-textos-padroes/index.view.html',
                controller: 'CotacoesTextosPadroes.IndexController',
                controllerAs: 'vm'
            })


            // CONFIGURAÇÕES -- AS's

            // CFOPS
            .state('cfops', {
                url: '/configuracoes/cfops',
                templateUrl: 'app/components/cfops/index.view.html',
                controller: 'Cfops.IndexController',
                controllerAs: 'vm'
            })
            .state('insert-cfops', {
                url: '/configuracoes/cfops/gerencia',
                templateUrl: 'app/components/cfops/gerencia.view.html',
                controller: 'Cfops.GerenciaController',
                controllerAs: 'vm'
            })
            .state('update-cfops', {
                url: '/configuracoes/cfops/gerencia/{id_cfop}?queryString',
                templateUrl: 'app/components/cfops/gerencia.view.html',
                controller: 'Cfops.GerenciaController',
                controllerAs: 'vm'
            })
            .state('cfops-paged', {
                url: '/configuracoes/cfops/{page}?queryString',
                templateUrl: 'app/components/cfops/index.view.html',
                controller: 'Cfops.IndexController',
                controllerAs: 'vm'
            })

            // CATEGORIAS DE TAXAS E LICENÇAS
            .state('categorias-de-taxas-e-licencas', {
                url: '/configuracoes/categorias-de-taxas-e-licencas',
                templateUrl: 'app/components/categorias-de-taxas-e-licencas/index.view.html',
                controller: 'CategoriasTaxasLicencas.IndexController',
                controllerAs: 'vm'
            })
            .state('insert-categorias-de-taxas-e-licencas', {
                url: '/configuracoes/categorias-de-taxas-e-licencas/gerencia',
                templateUrl: 'app/components/categorias-de-taxas-e-licencas/gerencia.view.html',
                controller: 'CategoriasTaxasLicencas.GerenciaController',
                controllerAs: 'vm'
            })
            .state('update-categorias-de-taxas-e-licencas', {
                url: '/configuracoes/categorias-de-taxas-e-licencas/gerencia/{id_categoria}?queryString',
                templateUrl: 'app/components/categorias-de-taxas-e-licencas/gerencia.view.html',
                controller: 'CategoriasTaxasLicencas.GerenciaController',
                controllerAs: 'vm'
            })
            .state('categorias-de-taxas-e-licencas-paged', {
                url: '/configuracoes/categorias-de-taxas-e-licencas/{page}?queryString',
                templateUrl: 'app/components/categorias-de-taxas-e-licencas/index.view.html',
                controller: 'CategoriasTaxasLicencas.IndexController',
                controllerAs: 'vm'
            })

            // TIPOS DE TAXAS E LICENÇAS
            .state('tipos-de-taxas-e-licencas', {
                url: '/configuracoes/tipos-de-taxas-e-licencas',
                templateUrl: 'app/components/tipos-de-taxas-e-licencas/index.view.html',
                controller: 'TiposTaxasLicencas.IndexController',
                controllerAs: 'vm'
            })
            .state('insert-tipos-de-taxas-e-licencas', {
                url: '/configuracoes/tipos-de-taxas-e-licencas/gerencia',
                templateUrl: 'app/components/tipos-de-taxas-e-licencas/gerencia.view.html',
                controller: 'TiposTaxasLicencas.GerenciaController',
                controllerAs: 'vm'
            })
            .state('update-tipos-de-taxas-e-licencas', {
                url: '/configuracoes/tipos-de-taxas-e-licencas/gerencia/{id_tipo}?queryString',
                templateUrl: 'app/components/tipos-de-taxas-e-licencas/gerencia.view.html',
                controller: 'TiposTaxasLicencas.GerenciaController',
                controllerAs: 'vm'
            })
            .state('tipos-de-taxas-e-licencas-paged', {
                url: '/configuracoes/tipos-de-taxas-e-licencas/{page}?queryString',
                templateUrl: 'app/components/tipos-de-taxas-e-licencas/index.view.html',
                controller: 'TiposTaxasLicencas.IndexController',
                controllerAs: 'vm'
            })

            // MOTIVO DO PRAZO DE PAGAMENTO
            .state('motivos-do-prazo-de-pagamento', {
                url: '/configuracoes/motivos-do-prazo-de-pagamento',
                templateUrl: 'app/components/motivos-do-prazo-de-pagamento/index.view.html',
                controller: 'MotivosPrazoPagamento.IndexController',
                controllerAs: 'vm'
            })
            .state('insert-motivos-do-prazo-de-pagamento', {
                url: '/configuracoes/motivos-do-prazo-de-pagamento/gerencia',
                templateUrl: 'app/components/motivos-do-prazo-de-pagamento/gerencia.view.html',
                controller: 'MotivosPrazoPagamento.GerenciaController',
                controllerAs: 'vm'
            })
            .state('update-motivos-do-prazo-de-pagamento', {
                url: '/configuracoes/motivos-do-prazo-de-pagamento/gerencia/{id_motivo}?queryString',
                templateUrl: 'app/components/motivos-do-prazo-de-pagamento/gerencia.view.html',
                controller: 'MotivosPrazoPagamento.GerenciaController',
                controllerAs: 'vm'
            })
            .state('motivos-do-prazo-de-pagamento-paged', {
                url: '/configuracoes/motivos-do-prazo-de-pagamento/{page}?queryString',
                templateUrl: 'app/components/motivos-do-prazo-de-pagamento/index.view.html',
                controller: 'MotivosPrazoPagamento.IndexController',
                controllerAs: 'vm'
            })

            // CONDIÇÕES DE PAGAMENTO
            .state('condicoes-de-pagamento', {
                url: '/configuracoes/condicoes-de-pagamento',
                templateUrl: 'app/components/condicoes-de-pagamento/index.view.html',
                controller: 'CondicoesPagamento.IndexController',
                controllerAs: 'vm'
            })
            .state('insert-condicoes-de-pagamento', {
                url: '/configuracoes/condicoes-de-pagamento/gerencia',
                templateUrl: 'app/components/condicoes-de-pagamento/gerencia.view.html',
                controller: 'CondicoesPagamento.GerenciaController',
                controllerAs: 'vm'
            })
            .state('update-condicoes-de-pagamento', {
                url: '/configuracoes/condicoes-de-pagamento/gerencia/{id_condicao}?queryString',
                templateUrl: 'app/components/condicoes-de-pagamento/gerencia.view.html',
                controller: 'CondicoesPagamento.GerenciaController',
                controllerAs: 'vm'
            })
            .state('condicoes-de-pagamento-paged', {
                url: '/configuracoes/condicoes-de-pagamento/{page}?queryString',
                templateUrl: 'app/components/condicoes-de-pagamento/index.view.html',
                controller: 'CondicoesPagamento.IndexController',
                controllerAs: 'vm'
            })

            ;
    }

    
})();