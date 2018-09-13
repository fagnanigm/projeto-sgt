(function () {
    'use strict';

    angular
        .module('app')
        .controller('Cotacoes.GerenciaController', Controller);

    function Controller($rootScope,$scope,$http,ngToast,$location,GlobalServices, $localStorage) {
        var vm = this;

        $scope.cotacao = {};

        initController();

        function get_cotacao(){

            if($rootScope.$state.name == "insert-cotacao"){

                $scope.cotacao = {
                    id_author : $localStorage.currentUser.id,
                    id_empresa : '0',
                    id_vendedor : '0',
                    id_categoria : '0',
                    id_forma_pagamento : '0',
                    cotacao_caracteristica : 'M',
                    cotacao_caracteristica_objetos : []
                }

            }else{

                $http.get('/api/public/cotacoes/get/' + $rootScope.$stateParams.id_cotacao + '?context=' + $localStorage.currentEmpresaId).then(function (response) {
                    
                    $scope.cotacao = response.data.cotacao;
                    $scope.cotacao.context = $localStorage.currentEmpresaId;

                }, function(response) {
                    $rootScope.is_error = true;
                    $rootScope.is_error_text = "Erro: " + response.data.error;
                }).finally(function() {
                    $rootScope.is_loading = false;
                });

            }

        }        

        function initController() {
            get_empresas();
            get_vendedores();
            get_categorias();
            get_formas_pagamento();
        	get_cotacao();
        }

        vm.setCotacao = function(){

            var error = 0;
            
            // Validação 
            if($scope.cotacao.cotacao_estado == '0'){
                ngToast.create({
                    className: 'danger',
                    content: "Estado inválido"
                });
                error++;
                return;
            }
    
            if(error == 0){

                $rootScope.is_loading = true;

                if($rootScope.$state.name == "insert-cotacao"){

                    $http.post('/api/public/cotacoes/insert', $scope.cotacao).then(function (response) {
                        
                        if(response.data.result){

                            ngToast.create({
                                className: 'success',
                                content: "Cotação cadastrado com sucesso!"
                            });

                            $location.path('/cotacoes');

                        }else{
                            ngToast.create({
                                className: 'danger',
                                content: response.data.error
                            });
                        }

                    }, function(response) {
                        $rootScope.is_error = true;
                        $rootScope.is_error_text = "Erro: " + response.data.message;
                    }).finally(function() {
                        $rootScope.is_loading = false;
                    });

                }else{

                    $http.post('/api/public/cotacoes/update', $scope.cotacao ).then(function (response) {

                        if(response.data.result){

                            ngToast.create({
                                className: 'success',
                                content: "Cotações editado com sucesso!"
                            });

                            $location.path('/cotacoes');

                        }else{
                            ngToast.create({
                                className: 'danger',
                                content: response.data.error
                            });
                        }

                    }, function(response) {
                        $rootScope.is_error = true;
                        $rootScope.is_error_text = "Erro: " + response.data.message;
                    }).finally(function() {
                        $rootScope.is_loading = false;
                    });


                }

            }

            
        }

        function get_empresas(){

            $http.get('/api/public/empresas/get?getall=1').then(function (response) {
                $scope.empresas = response.data.results;
            }).finally(function() {
                $rootScope.is_loading = false;
            });

        }

        function get_vendedores(){

            $http.get('/api/public/vendedores/get?getall=1').then(function (response) {
                $scope.vendedores = response.data.results;
            }).finally(function() {
                $rootScope.is_loading = false;
            });

        }

        function get_categorias(){

            $http.get('/api/public/categorias/get?getall=1').then(function (response) {
                $scope.categorias = response.data.results;
            }).finally(function() {
                $rootScope.is_loading = false;
            });

        }

        function get_formas_pagamento(){

            $http.get('/api/public/formas-pagamento/get?getall=1').then(function (response) {
                $scope.formas_pagamento = response.data.results;
            }).finally(function() {
                $rootScope.is_loading = false;
            });

        }

        /// Seleções dos clientes
        $scope.cliente_search_filter = { free_term : '' };
        $scope.cliente_is_search = false;

        $scope.search_modal_cliente = function(){
            $rootScope.is_modal_loading = true;
            
            $http.get('/api/public/clientes/search?term=' + $scope.cliente_search_filter.free_term).then(function (response) {
                
                if(response.data.result){
                    $scope.clientes = response.data.results;
                    $scope.cliente_is_search = true;
                }else{
                    ngToast.create({
                        className: 'danger',
                        content: response.data.error
                    });
                }

            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.message;
            }).finally(function() {
                $rootScope.is_modal_loading = false;
            });

        }

        $scope.open_choose_cliente = function(fn){
            $scope.open_choose_cliente_fn = fn;
            $rootScope.openModal("/app/components/cotacoes/clientes.modal.html",false,$scope);
        }

        $scope.choose_cliente = function(cliente){
            $scope[$scope.open_choose_cliente_fn](cliente);
            $rootScope.closeModal();
            $scope.cliente_is_search = false;
        }

        $scope.main_cliente = function(cliente){
            $scope.cotacao.cliente_nome = cliente.cliente_nome;
        }

        /// Salva objeto na cotação
        $scope.open_objeto_form = function(){
            $scope.objeto = {};
            $rootScope.openModal("/app/components/cotacoes/objeto-form.modal.html",false,$scope);
        }

        $scope.save_cotacao_objeto = function(){
            $scope.cotacao.cotacao_caracteristica_objetos.push($scope.objeto);
            $scope.objeto = {};
            $rootScope.closeModal();

            ngToast.create({
                className: 'success',
                content: 'Objeto incluso com sucesso!'
            });

        } 


        
    }

})();