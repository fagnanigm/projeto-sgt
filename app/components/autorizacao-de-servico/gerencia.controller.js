(function () {
    'use strict';

    angular
        .module('app')
        .controller('As.GerenciaController', Controller);

    function Controller($rootScope,$scope,$http,ngToast,$location,GlobalServices, $localStorage) {
        var vm = this;

        $scope.as = {};

        initController();

        function get_as(){

            if($rootScope.$state.name == "insert-autorizacao-de-servico"){

                $scope.as = {
                    id_author : $localStorage.currentUser.id,
                    id_empresa : $localStorage.currentEmpresaId,
                    as_revisao : '0',
                    as_filial : '0',
                    as_status : 'status1',
                    as_data_cadastro_obj : new Date(),
                    as_cliente_tomador_type : 'consignatario'
                }


                // Busca código
                /*
                $http.get('/api/public/as/getnextcode' + '?context=' + $localStorage.currentEmpresaId).then(function (response) {
                    $scope.as.as_codigo_seq = response.data.code;
                    $scope.as.as_codigo = $scope.as.as_codigo_seq + '/' + $scope.as.as_filial + '-' + $scope.as.as_revisao;
                });

                $scope.$watchGroup(['as.as_revisao', 'as.as_filial'], function() { 
                    $scope.as.as_codigo = $scope.as.as_codigo_seq + '/' + $scope.as.as_filial + '-' + $scope.as.as_revisao;
                });    
                */        

                $rootScope.is_loading = false;

            }else{

                $http.get('/api/public/locais/get/' + $rootScope.$stateParams.id_as + '?context=' + $localStorage.currentEmpresaId).then(function (response) {
                    
                    $scope.as = response.data.as;
                    $scope.as.context = $localStorage.currentEmpresaId;

                }, function(response) {
                    $rootScope.is_error = true;
                    $rootScope.is_error_text = "Erro: " + response.data.error;
                }).finally(function() {
                    $rootScope.is_loading = false;
                });

            }

        }        

        function initController() {

        	get_as();
        }
        

        vm.setAs = function(){

            var error = 0;
            
            // Validação

    
            if(error == 0){

                $rootScope.is_loading = true;

                $scope.as.as_codigo_seq = GlobalServices.get_as_code_sequencial($scope.as.as_codigo);
            
                if($rootScope.$state.name == "insert-autorizacao-de-servico"){

                    $http.post('/api/public/as/insert', $scope.as).then(function (response) {
                        
                        if(response.data.result){

                            ngToast.create({
                                className: 'success',
                                content: "AS cadastrada com sucesso!"
                            });

                            $location.path('/autorizacao-de-servico');

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

                    $http.post('/api/public/locais/update', $scope.as ).then(function (response) {
                        
                        if(response.data.result){

                            ngToast.create({
                                className: 'success',
                                content: "as editado com sucesso!"
                            });

                            $location.path('/locais');

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


        /// Seleções dos clientes

        $scope.cliente_search_filter = { free_term : '' };
        $scope.cliente_is_search = false;
        $scope.cliente_consig_data = false;
        $scope.cliente_destinatario_data = false;
        $scope.cliente_remetente_data = false;
        $scope.cliente_faturamento_data = false;

        $scope.search_modal_cliente = function(){
            $rootScope.is_modal_loading = true;
            
            $http.get('/api/public/clientes/search?term=' + $scope.cliente_search_filter.free_term + '&context=' + $localStorage.currentEmpresaId, $scope.as ).then(function (response) {
                
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
            $rootScope.openModal("/app/components/autorizacao-de-servico/clientes.modal.html",false,$scope);
        }

        $scope.choose_cliente = function(cliente){
            $scope[$scope.open_choose_cliente_fn](cliente);
            $rootScope.closeModal();
            $scope.cliente_is_search = false;
        }

        // Main cliente: primeira parte do cadastro da AS
        $scope.main_cliente = function(cliente){
            $scope.as.as_cliente_id = cliente.id;
            $scope.as.as_cliente_fantasia = cliente.cliente_nome;
            $scope.as.as_cliente_responsavel = cliente.cliente_nome;
            $scope.as.as_cliente_email = cliente.cliente_email;
            $scope.as.as_cliente_phone1 = '(' + cliente.cliente_phone_01_ddd + ') ' + cliente.cliente_phone_01;
        }

        // Cliente consignatario
        $scope.cliente_consignatario = function(cliente){
            $scope.as.as_id_cliente_consig = cliente.id;
            $scope.cliente_consig_data = cliente;
        }

        // Cliente remetente
        $scope.cliente_remetente = function(cliente){
            $scope.as.as_id_cliente_remetente = cliente.id;
            $scope.cliente_remetente_data = cliente;
        }

        // Cliente destinatario
        $scope.cliente_destinatario = function(cliente){
            $scope.as.as_id_cliente_destinatario = cliente.id;
            $scope.cliente_destinatario_data = cliente;
        }

        // Cliente faturamento
        $scope.cliente_faturamento = function(cliente){
            $scope.as.as_id_cliente_faturamento = cliente.id;
            $scope.cliente_faturamento_data = cliente;
        }

        // Seleção de projeto

        $scope.projeto_search_filter = { free_term : '' };
        $scope.projeto_is_search = false;

        $scope.search_modal_projeto = function(){
            $rootScope.is_modal_loading = true;

            $http.get('/api/public/projetos/search?term=' + $scope.projeto_search_filter.free_term + '&context=' + $localStorage.currentEmpresaId, $scope.as ).then(function (response) {
                    
                if(response.data.result){
                    $scope.projetos = response.data.results;
                    $scope.projeto_is_search = true;
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

        $scope.open_choose_projeto = function(fn){
            $scope.open_choose_projeto_fn = fn;
            $rootScope.openModal("/app/components/autorizacao-de-servico/projetos.modal.html",false,$scope);
        }

        $scope.choose_projeto = function(projeto){
            $scope[$scope.open_choose_projeto_fn](projeto);
            $rootScope.closeModal();
            $scope.projeto_is_search = false;
        }

        $scope.main_projeto = function(projeto){
            $scope.as.as_id_projeto = projeto.id;
            $scope.as.as_projeto_nome = projeto.projeto_apelido;
            $scope.as.as_projeto_cod = projeto.projeto_codigo;
            $scope.as.as_projeto_resumo = projeto.projeto_resumo;
        }

        // Seleção de local

        $scope.local_search_filter = { free_term : '' };
        $scope.local_is_search = false;
        $scope.local_coleta_data = false;
        $scope.local_entrega_data = false;

        $scope.search_modal_local = function(){
            $rootScope.is_modal_loading = true;

            $http.get('/api/public/locais/search?term=' + $scope.local_search_filter.free_term + '&context=' + $localStorage.currentEmpresaId, $scope.as ).then(function (response) {
                    
                if(response.data.result){
                    $scope.locais = response.data.results;
                    $scope.local_is_search = true;
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

        $scope.open_choose_local = function(fn){
            $scope.open_choose_local_fn = fn;
            $rootScope.openModal("/app/components/autorizacao-de-servico/locais.modal.html",false,$scope);
        }

        $scope.choose_local = function(local){
            $scope[$scope.open_choose_local_fn](local);
            $rootScope.closeModal();
            $scope.local_is_search = false;
        }

        // Local de coleta
        $scope.local_coleta = function(local){
            $scope.as.as_id_local_coleta = local.id;
            $scope.local_coleta_data = local;
        }

        // Local entrega
        $scope.local_entrega = function(local){
            $scope.as.as_id_local_entrega = local.id;
            $scope.local_entrega_data = local;
        }

        // Tabs
        $('#tabs-gerencia-as a').on('click', function (e) {
            e.preventDefault()
            $(this).tab('show')
        });

        
    }

})();