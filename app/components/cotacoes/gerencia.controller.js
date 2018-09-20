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
                    id_cliente : '0',
                    cotacao_revisao : '0',
                    cotacao_status : '0',
                    cotacao_caracteristica_objetos : [],
                    cotacao_cadastro_data_obj : new Date()
                }

                setTimeout(function(){
                    $rootScope.is_loading = false;
                },2000);

            }else{

                $http.get('/api/public/cotacoes/get/' + $rootScope.$stateParams.id_cotacao ).then(function (response) {

                    $scope.cotacao = response.data.cotacao;
                    $scope.cotacao.cotacao_revisao =  parseInt($scope.cotacao.cotacao_revisao) + 1;
                    $scope.cotacao.id_revisao = ($scope.cotacao.cotacao_revisao == 1 ? $scope.cotacao.id : $scope.cotacao.id_revisao );
                    $scope.cotacao.cotacao_cadastro_data_obj = new Date($scope.cotacao.cotacao_cadastro_data * 1000);

                    calc_total_cotacao_objeto();

                    get_cotacao_code();

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

            if($scope.cotacao.id_cliente == '0'){
                ngToast.create({
                    className: 'danger',
                    content: "Selecione um cliente"
                });
                error++;
                return;
            }

            if($scope.cotacao.id_empresa == '0'){
                ngToast.create({
                    className: 'danger',
                    content: "Selecione uma filial"
                });
                error++;
                return;
            }

            if($scope.cotacao.id_vendedor == '0'){
                ngToast.create({
                    className: 'danger',
                    content: "Selecione um vendedor"
                });
                error++;
                return;
            }

            if($scope.cotacao.id_categoria == '0'){
                ngToast.create({
                    className: 'danger',
                    content: "Selecione uma categoria"
                });
                error++;
                return;
            }

            if($scope.cotacao.cotacao_status == '0'){
                ngToast.create({
                    className: 'danger',
                    content: "Selecione um status"
                });
                error++;
                return;
            }

            if(error == 0){

                $rootScope.is_loading = true;

                $scope.cotacao.cotacao_cadastro_data = Math.floor($scope.cotacao.cotacao_cadastro_data_obj.getTime() / 1000);

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

                
            }

            
        }

        function get_empresas(){

            $http.get('/api/public/empresas/get?getall=1').then(function (response) {
                $scope.empresas = response.data.results;
            });

        }

        function get_vendedores(){

            $http.get('/api/public/vendedores/get?getall=1').then(function (response) {
                $scope.vendedores = response.data.results;
            });

        }

        function get_categorias(){

            $http.get('/api/public/categorias/get?getall=1').then(function (response) {
                $scope.categorias = response.data.results;
            });

        }

        function get_formas_pagamento(){

            $http.get('/api/public/formas-pagamento/get?getall=1').then(function (response) {
                $scope.formas_pagamento = response.data.results;
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
            $scope.cotacao.cotacao_cliente_nome = cliente.cliente_nome;
            $scope.cotacao.id_cliente = cliente.id;
        }

        /// Salva objeto na cotação
        $scope.open_objeto_form = function(){
            $scope.objeto = {
                objeto_tipo_valor : 'reais',
                is_edit : false
            };
            $rootScope.openModal("/app/components/cotacoes/objeto-form.modal.html",false,$scope);
        }

        $scope.save_cotacao_objeto = function(){

            if(!$scope.objeto.is_edit){
                $scope.cotacao.cotacao_caracteristica_objetos.push($scope.objeto);
            }else{
                $scope.cotacao.cotacao_caracteristica_objetos[$scope.objeto.key] = $scope.objeto;
            }
            
            $scope.objeto = {};
            $rootScope.closeModal();
            calc_total_cotacao_objeto();

            ngToast.create({
                className: 'success',
                content: 'Objeto incluso com sucesso!'
            });

        } 

        $scope.edit_objeto = function(key){
            $scope.objeto = $scope.cotacao.cotacao_caracteristica_objetos[key];
            $scope.objeto.is_edit = true;
            $scope.objeto.key = key;
            $rootScope.openModal("/app/components/cotacoes/objeto-form.modal.html",false,$scope);
        }

        $scope.remove_objeto = function(key){
            if(confirm("Deseja remover esse objeto?")){
                $scope.cotacao.cotacao_caracteristica_objetos.splice(key, 1);
                calc_total_cotacao_objeto();
            }
        }

        function calc_total_cotacao_objeto(){

            $scope.total_cotacao_objetos = 0;
            $.each($scope.cotacao.cotacao_caracteristica_objetos, function(key, val){
                $scope.total_cotacao_objetos += parseFloat(val.objeto_valor_total);
            });

        }


        // Obtem código automaticamente
        $scope.empresa_change = function(){

            if($scope.cotacao.id_empresa != '0'){ 

                get_cotacao_code();

            }

        }

        function get_cotacao_code(){

            $rootScope.is_loading = true;

            var param = {
                id_empresa : $scope.cotacao.id_empresa,
                revisao : $scope.cotacao.cotacao_revisao
            }

            if($rootScope.$state.name == "update-cotacao"){
                param.cotacao_code_sequencial = $scope.cotacao.cotacao_code_sequencial;
            }

            $http.post('/api/public/cotacoes/getnextcode', param).then(function (response) {
            
                if(response.data.result){

                    $scope.cotacao.cotacao_code = response.data.cotacao_code;
                    $scope.cotacao.cotacao_code_sequencial = response.data.cotacao_code_sequencial;
                    
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

})();