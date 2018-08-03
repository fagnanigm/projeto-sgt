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
                controller: 'Home.IndexController',
                controllerAs: 'vm'
            })
            .state('insert-empresa', {
                url: '/cadastrar-empresa',
                templateUrl: 'app/components/empresas/cadastro-empresa.view.html',
                controller: 'Home.IndexController',
                controllerAs: 'vm'
            })

            // DASHBOARD

            .state('dashboard', {
                url: '/dashboard',
                templateUrl: 'app/components/home/index.view.html',
                controller: 'Home.IndexController',
                controllerAs: 'vm'
            })

            // PRODUTOS 

            .state('produtos', {
                url: '/produtos',
                templateUrl: 'app/components/produtos/index.view.html',
                controller: 'Produtos.IndexController',
                controllerAs: 'vm'
            })
            .state('insert-produto', {
                url: '/produtos/gerencia',
                templateUrl: 'app/components/produtos/gerencia.view.html',
                controller: 'Produtos.GerenciaController',
                controllerAs: 'vm'
            })
            .state('visualizar-produto', {
                url: '/produtos/visualizar',
                templateUrl: 'app/components/produtos/visualizar.view.html',
                controller: 'Produtos.GerenciaController',
                controllerAs: 'vm'
            })
            .state('importar-produto', {
                url: '/produtos/importar-do-omie',
                templateUrl: 'app/components/produtos/importar.view.html',
                controller: 'Usuarios.GerenciaController',
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
                controller: 'Usuarios.GerenciaController',
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
                controller: 'Clientes.IndexController',
                controllerAs: 'vm'
            })
            .state('visualizar-cliente', {
                url: '/clientes/visualizar',
                templateUrl: 'app/components/clientes/visualizar.view.html',
                controller: 'Clientes.IndexController',
                controllerAs: 'vm'
            })


            // LOCAIS

            .state('locais', {
                url: '/locais',
                templateUrl: 'app/components/locais/index.view.html',
                controller: 'Produtos.IndexController',
                controllerAs: 'vm'
            })
            .state('insert-local', {
                url: '/locais/gerencia',
                templateUrl: 'app/components/locais/gerencia.view.html',
                controller: 'Produtos.IndexController',
                controllerAs: 'vm'
            })
            .state('visualizar-local', {
                url: '/locais/visualizar',
                templateUrl: 'app/components/locais/visualizar.view.html',
                controller: 'Produtos.IndexController',
                controllerAs: 'vm'
            })

            // VEICULOS

            .state('veiculos', {
                url: '/veiculos',
                templateUrl: 'app/components/veiculos/index.view.html',
                controller: 'Produtos.IndexController',
                controllerAs: 'vm'
            })
            .state('insert-veiculo', {
                url: '/veiculos/gerencia',
                templateUrl: 'app/components/veiculos/gerencia.view.html',
                controller: 'Produtos.IndexController',
                controllerAs: 'vm'
            })

            .state('visualizar-veiculo', {
                url: '/veiculos/visualizar',
                templateUrl: 'app/components/veiculos/visualizar.view.html',
                controller: 'Produtos.IndexController',
                controllerAs: 'vm'
            })

            // MOTORISTAS

            .state('motoristas', {
                url: '/motoristas',
                templateUrl: 'app/components/motoristas/index.view.html',
                controller: 'Produtos.IndexController',
                controllerAs: 'vm'
            })

            .state('insert-motoristas', {
                url: '/motoristas/gerencia',
                templateUrl: 'app/components/motoristas/gerencia.view.html',
                controller: 'Produtos.IndexController',
                controllerAs: 'vm'
            })

            .state('visualizar-motoristas', {
                url: '/motoristas/visualizar',
                templateUrl: 'app/components/motoristas/visualizar.view.html',
                controller: 'Produtos.IndexController',
                controllerAs: 'vm'
            })


            // PROJETOS

            .state('projetos', {
                url: '/projetos',
                templateUrl: 'app/components/projetos/index.view.html',
                controller: 'Produtos.IndexController',
                controllerAs: 'vm'
            })

            .state('insert-projeto', {
                url: '/projetos/gerencia',
                templateUrl: 'app/components/projetos/gerencia.view.html',
                controller: 'Produtos.IndexController',
                controllerAs: 'vm'
            })

            .state('visualizar-projeto', {
                url: '/projetos/visualizar',
                templateUrl: 'app/components/projetos/visualizar.view.html',
                controller: 'Produtos.IndexController',
                controllerAs: 'vm'
            })

            // AS

            .state('autorizacao-de-servico', {
                url: '/autorizacao-de-servico',
                templateUrl: 'app/components/autorizacao-de-servico/index.view.html',
                controller: 'Produtos.IndexController',
                controllerAs: 'vm'
            })

            .state('insert-autorizacao-de-servico', {
                url: '/autorizacao-de-servico/gerencia',
                templateUrl: 'app/components/autorizacao-de-servico/gerencia.view.html',
                controller: 'Produtos.IndexController',
                controllerAs: 'vm'
            })

            .state('visualizar-autorizacao-de-servico', {
                url: '/autorizacao-de-servico/visualizar',
                templateUrl: 'app/components/autorizacao-de-servico/visualizar.view.html',
                controller: 'Produtos.IndexController',
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
                controller: 'Relatorios.IndexController',
                controllerAs: 'vm'
            });
    }

    
})();