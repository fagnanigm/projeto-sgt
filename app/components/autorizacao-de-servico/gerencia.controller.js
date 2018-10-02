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
                    id_empresa : '0',
                    as_projeto_status : '0',
                    id_vendedor : '0',
                    id_categoria : '0',
                    as_projeto_revisao : '0',
                    as_projeto_cadastro_data_obj : new Date(),
                    as_projeto_status : 'em-aberto',
                    id_author : $localStorage.currentUser.id,
                    as_as_valor_retido : 'N',
                    as_objetos_carregamento : [],
                    as_dados_carga : [],
                    as_valor_total_bruto : 0,
                    as_valor_liquido_receber : 0,
                    as_valor_resultado_bruto : 0,
                    as_valor_resultado_liquido : 0,
                    as_as_prazo_pagamento_id : '0'
                }

                var id_projeto = $location.search().projeto; 

                if(id_projeto){

                    $http.get('/api/public/projetos/get/' + id_projeto).then(function (response) {

                        var projeto = response.data.projeto;

                        $scope.as.id_cotacao = projeto.id_cotacao;
                        $scope.as.id_projeto = projeto.id;
                        $scope.as.id_empresa = projeto.id_empresa;
                        $scope.as.id_vendedor = projeto.id_vendedor;
                        $scope.as.id_categoria = projeto.id_categoria;
                        $scope.as.id_cliente = projeto.id_cliente;
                        $scope.as.as_projeto_cliente_nome = projeto.projeto_cliente_nome;
                        $scope.as.as_projeto_contato = projeto.projeto_contato;
                        $scope.as.as_projeto_email = projeto.projeto_email;
                        $scope.as.as_projeto_phone_01 = projeto.projeto_phone_01;
                        $scope.as.as_projeto_phone_02 = projeto.projeto_phone_02;
                        $scope.as.as_projeto_phone_03 = projeto.projeto_phone_03;
                        $scope.as.as_projeto_ramal = projeto.projeto_ramal;
                        $scope.as.as_projeto_nome = projeto.projeto_nome;
                        $scope.as.as_projeto_descricao = projeto.projeto_descricao;

                        get_as_code();

                    }, function(response) {
                        $rootScope.is_error = true;
                        $rootScope.is_error_text = "Erro: " + response.data.error;
                    }).finally(function() {
                        $rootScope.is_loading = false;
                    });

                }else{

                    $rootScope.is_loading = false;
                }

            }else{

                $http.get('/api/public/as/get/' + $rootScope.$stateParams.id_as).then(function (response) {
                    
                    $scope.as = response.data.as; 

                    console.log($scope.as);             

                    $scope.as.as_projeto_revisao =  parseInt($scope.as.as_projeto_revisao) + 1;
                    $scope.as.id_revisao = ($scope.as.as_projeto_revisao == 1 ? $scope.as.id : $scope.as.id_revisao );             
                    $scope.as.as_projeto_cadastro_data_obj = ($scope.as.as_projeto_cadastro_data.length > 0 ? new Date($scope.as.as_projeto_cadastro_data * 1000) : new Date() );
                    $scope.as.as_dados_carga = [];

                    delete $scope.as.id;
                    
                    get_as_code();
                    calc_total_as_objeto();

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
            get_prazos_pg();
        	get_as();
        }
        

        vm.setAs = function(){

            var error = 0;
            
            // Validação
    
            if(error == 0){

                $rootScope.is_loading = true;   

                $scope.as.as_projeto_cadastro_data = Math.floor($scope.as.as_projeto_cadastro_data_obj.getTime() / 1000);

                // DATA : as_as_prazo_pagamento_obj
                if($scope.as.as_as_prazo_pagamento_obj){
                    $scope.as.as_as_prazo_pagamento = Math.floor($scope.as.as_as_prazo_pagamento_obj.getTime() / 1000);
                }          

                $http.post('/api/public/as/insert', $scope.as).then(function (response) {

                    console.log(response);
                                        
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
            
        }

        // Cliente consignatario
        $scope.cliente_consignatario = function(cliente){
            $scope.as.as_as_id_cliente_faturamento = cliente.id;
            $scope.as.as_as_cliente_faturamento_nome = cliente.cliente_nome;
            $scope.cliente_consig_data = cliente;
        }

        // Cliente remetente
        $scope.cliente_remetente = function(cliente){
            $scope.as.as_op_id_cliente_remetente = cliente.id;
            $scope.as.as_op_cliente_remetente_nome = cliente.cliente_nome;
            $scope.cliente_remetente_data = cliente;
        }

        // Cliente destinatario
        $scope.cliente_destinatario = function(cliente){
            $scope.as.as_op_id_cliente_destinatario = cliente.id;
            $scope.as.as_op_cliente_destinatario_nome = cliente.cliente_nome;
            $scope.cliente_destinatario_data = cliente;
        }

        // Cliente faturamento
        $scope.cliente_faturamento = function(cliente){
            $scope.as.as_id_cliente_faturamento = cliente.id;
            $scope.cliente_faturamento_data = cliente;
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

        // Objeto de carregamento
        $scope.open_objeto_form = function(){
            $scope.objeto = {
                objeto_tipo_valor : 'reais',
                is_edit : false
            };
            $rootScope.openModal("/app/components/autorizacao-de-servico/objeto-form.modal.html",false,$scope);
        }

        $scope.save_as_objeto = function(){

            if(!$scope.objeto.is_edit){
                $scope.as.as_objetos_carregamento.push($scope.objeto);
            }else{
                $scope.as.as_objetos_carregamento[$scope.objeto.key] = $scope.objeto;
            }
            
            $scope.objeto = {};
            $rootScope.closeModal();
            calc_total_as_objeto();

            ngToast.create({
                className: 'success',
                content: 'Objeto incluso com sucesso!'
            });

        }

        $scope.edit_objeto = function(key){
            $scope.objeto = $scope.as.as_objetos_carregamento[key];
            $scope.objeto.is_edit = true;
            $scope.objeto.key = key;
            $rootScope.openModal("/app/components/autorizacao-de-servico/objeto-form.modal.html",false,$scope);
        }

        $scope.remove_objeto = function(key){
            if(confirm("Deseja remover esse objeto?")){
                $scope.as.as_objetos_carregamento.splice(key, 1);
                calc_total_as_objeto();
            }
        }

        function calc_total_as_objeto(){
            
            $scope.total_as_objetos = 0;
            $.each($scope.as.as_objetos_carregamento, function(key, val){
                $scope.total_as_objetos += parseFloat(val.objeto_valor_total);
            });



            $scope.as.as_valor_total_bruto = $scope.total_as_objetos;

        }

        // Dados da carga
        $scope.open_carga_form = function(){
            $scope.carga = {
                is_edit : false
            };
            $rootScope.openModal("/app/components/autorizacao-de-servico/dados-carga-form.modal.html",false,$scope);
        }

        $scope.save_as_carga = function(){
            $scope.as.as_dados_carga.push($scope.carga)

            $scope.carga = {}
            $rootScope.closeModal();

            ngToast.create({
                className: 'success',
                content: 'Carga inclusa com sucesso!'
            });

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

        function get_prazos_pg(){

            $http.get('/api/public/autorizacao-servico-prazo-pg/get?getall=1').then(function (response) {
                $scope.prazos_pgs = response.data.results;
            });

        }

        

        function get_as_code(){

            $rootScope.is_loading = true;

            var param = {
                id_empresa : $scope.as.id_empresa,
                revisao : $scope.as.as_projeto_revisao
            }

            if($rootScope.$state.name == "update-autorizacao-de-servico"){
                param.as_projeto_code_sequencial = $scope.as.as_projeto_code_sequencial;
            }

            $http.post('/api/public/as/getnextcode', param ).then(function (response) {
            
                if(response.data.result){

                    $scope.as.as_projeto_code = response.data.as_projeto_code;
                    $scope.as.as_projeto_code_sequencial = response.data.as_projeto_code_sequencial;
                    
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