(function () {
    'use strict';

    angular
        .module('app')
        .controller('As.GerenciaController', Controller);

    function Controller($rootScope,$scope,$http,ngToast,$location,GlobalServices, $localStorage) {

        // Protect to change
        $scope.allow_change_page = false;
        $scope.$on('onBeforeUnload', function (e, confirmation) {
            confirmation.message = "Todos os dados que foram alterados serão perdidos.";
            e.preventDefault();
        });

        $scope.$on('$locationChangeStart', function (e, next, current) {
            if(!$scope.allow_change_page){
                if(!confirm("Todos os dados que foram alterados serão perdidos. Deseja prosseguir?")){
                    $rootScope.is_loading = false;
                    e.preventDefault();
                }  
            }
        });

        // Finish protect to change

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
                    as_valor_custos_depesas : 0,
                    as_as_prazo_pagamento_id : '0',
                    as_as_prazo_razao_id : '0',
                    // Valores fiscais
                    as_as_incluso_contabil_inss_percent : '3.50',
                    as_as_incluso_contabil_ir_percent : '1.50',
                    as_as_incluso_contabil_pis_percent : '0.65',
                    as_as_incluso_contabil_cofins_percent : '3.00',
                    as_as_incluso_contabil_csll_percent : '1.00',
                    as_as_incluso_contabil_cp_percent : '1.50',
                    as_as_incluso_contabil_inss_percent_root : '35.00',
                    as_as_incluso_contabil_iss_percent : '0.00',
                    as_as_incluso_contabil_icms_percent : '0.00',
                    as_as_incluso_comercial_advalorem_percent : '0.00',
                    as_as_incluso_comercial_rcfdc_percent : '0.00',
                    as_as_incluso_comercial_rctrc_percent : '0.00',
                    as_as_incluso_comercial_icms_percent : '0.00',
                    as_as_incluso_comercial_iss_percent : '0.00',
                    as_as_incluso_contabil_ir : true,
                    as_as_incluso_contabil_pis : true,
                    as_as_incluso_contabil_cofins : true,
                    as_as_incluso_contabil_csll : true,
                    as_as_incluso_contabil_cp : true,
                    // Frotas
                    as_assoc_frotas : [],
                    // Motoristas 
                    as_assoc_motoristas : [],
                    as_taxas_licencas : []
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

                        $scope.as.as_as_prazo_razao_id = projeto.cotacao_prazo_razao_id;
                        $scope.as.as_as_prazo_pagamento_id = projeto.cotacao_condicoes_pagamento_id;


                        if(projeto.cotacoes_objetos){

                            $.each(projeto.cotacoes_objetos, function(key, val){
                                $scope.as.as_objetos_carregamento.push(val);
                            });

                        }

                        console.log(projeto);

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

                    $scope.as.as_projeto_revisao =  parseInt($scope.as.as_projeto_revisao) + 1;
                    $scope.as.id_revisao = ($scope.as.as_projeto_revisao == 1 ? $scope.as.id : $scope.as.id_revisao );             
                    $scope.as.as_projeto_cadastro_data_obj = ($scope.as.as_projeto_cadastro_data.length > 0 ? new Date($scope.as.as_projeto_cadastro_data * 1000) : new Date() );

                    // DATA
                    $scope.as.as_op_data_carregamento_obj = (String($scope.as.as_op_data_carregamento).length > 0 ? new Date($scope.as.as_op_data_carregamento * 1000) : new Date() );
                    $scope.as.as_op_data_previsao_obj = (String($scope.as.as_op_data_previsao).length > 0 ? new Date($scope.as.as_op_data_previsao * 1000) : new Date() );
                    $scope.as.as_fat_data_faturamento_obj = (String($scope.as.as_fat_data_faturamento).length > 0 ? new Date($scope.as.as_fat_data_faturamento * 1000) : new Date() );
                    $scope.as.as_fat_data_envio_obj = (String($scope.as.as_fat_data_envio).length > 0 ? new Date($scope.as.as_fat_data_envio * 1000) : new Date() );
                    $scope.as.as_fat_data_vencimento_obj = (String($scope.as.as_fat_data_vencimento).length > 0 ? new Date($scope.as.as_fat_data_vencimento * 1000) : new Date() );



                    // Dados dos clientes
                    $scope.cliente_consig_data = $scope.as.cliente_consig_data;
                    $scope.cliente_remetente_data = $scope.as.cliente_remetente_data;
                    $scope.cliente_destinatario_data = $scope.as.cliente_destinatario_data;
                    $scope.cliente_faturamento_data = $scope.as.cliente_faturamento_data;
                    $scope.cliente_cobranca_data = $scope.as.cliente_cobranca_data;

                    // Dados dos locais
                    $scope.local_coleta_data = $scope.as.local_coleta_data;
                    $scope.local_entrega_data = $scope.as.local_entrega_data;

                    // Dados da CFOP
                    $scope.cfop_data = $scope.as.cfop_data;

                    $scope.as.as_valor_custos_depesas = 0;

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
            get_prazo_razoes();
            get_taxas_tipos();
            get_taxas_categorias();
        	get_as();
        }
        

        vm.setAs = function(){

            var error = 0;
            
            // Validação
    
            if(error == 0){

                $rootScope.is_loading = true;   

                $scope.as.as_projeto_cadastro_data = Math.floor($scope.as.as_projeto_cadastro_data_obj.getTime() / 1000);

                // DATA : as_op_data_carregamento_obj
                if($scope.as.as_op_data_carregamento_obj){
                    $scope.as.as_op_data_carregamento = Math.floor($scope.as.as_op_data_carregamento_obj.getTime() / 1000);
                }          

                // DATA : as_op_data_previsao
                if($scope.as.as_op_data_previsao_obj){
                    $scope.as.as_op_data_previsao = Math.floor($scope.as.as_op_data_previsao_obj.getTime() / 1000);
                }

                // DATA : as_fat_data_faturamento
                if($scope.as.as_fat_data_faturamento_obj){
                    $scope.as.as_fat_data_faturamento = Math.floor($scope.as.as_fat_data_faturamento_obj.getTime() / 1000);
                }

                // DATA : as_fat_data_envio
                if($scope.as.as_fat_data_envio_obj){
                    $scope.as.as_fat_data_envio = Math.floor($scope.as.as_fat_data_envio_obj.getTime() / 1000);
                }

                // DATA : as_fat_data_vencimento_obj
                if($scope.as.as_fat_data_vencimento_obj){
                    $scope.as.as_fat_data_vencimento = Math.floor($scope.as.as_fat_data_vencimento_obj.getTime() / 1000);
                }

                $http.post('/api/public/as/insert', $scope.as).then(function (response) {

                    console.log(response);
                                        
                    if(response.data.result){

                        $scope.allow_change_page = true;

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
        $scope.cliente_cobranca_data = false;

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
            $scope.as.as_fat_id_cliente_faturamento = cliente.id;
            $scope.as.as_fat_cliente_faturamento_nome = cliente.cliente_nome;
            $scope.cliente_faturamento_data = cliente;
        }

        // Cliente cobrança
        $scope.cliente_cobranca = function(cliente){
            $scope.as.as_fat_id_cliente_cobranca = cliente.id;
            $scope.as.as_fat_cliente_cobranca_nome = cliente.cliente_nome;
            $scope.cliente_cobranca_data = cliente;
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
            $scope.as.as_op_id_local_coleta = local.id;
            $scope.as.as_op_local_coleta_nome = local.local_nome;
            $scope.local_coleta_data = local;
        }

        // Local entrega
        $scope.local_entrega = function(local){
            $scope.as.as_op_id_local_entrega = local.id;
            $scope.as.as_op_local_entrega_nome = local.local_nome;
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
                is_edit : false,
                objeto_item : ($scope.as.as_objetos_carregamento.length + 1)
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
            
            // Soma os valores dos objetos de carregamento
            $scope.total_as_objetos = 0;
            $scope.total_mercadoria_as_objetos = 0;
            $scope.as.as_valor_custos_depesas = 0;

            // Calcula de taxas
            $.each($scope.as.as_taxas_licencas, function(key, val){
                $scope.as.as_valor_custos_depesas += parseFloat(val.taxa_valor);
            });

            $.each($scope.as.as_objetos_carregamento, function(key, val){
                $scope.total_as_objetos += parseFloat(val.objeto_valor_total);
                $scope.total_mercadoria_as_objetos += parseFloat(val.objeto_valor_mercadoria_total);
            });

            $scope.as.as_valor_total_bruto = $scope.total_as_objetos;

            // soma valores "inclusos no preço comercial"
            if($scope.as.as_as_incluso_comercial_icms){
                if(typeof($scope.as.as_as_incluso_comercial_icms_valor) != 'undefined'){
                    $scope.as.as_valor_total_bruto += parseFloat($scope.as.as_as_incluso_comercial_icms_valor);
                }
            }

            if($scope.as.as_as_incluso_comercial_iss){
                if(typeof($scope.as.as_as_incluso_comercial_iss_valor) != 'undefined'){
                    $scope.as.as_valor_total_bruto += parseFloat($scope.as.as_as_incluso_comercial_iss_valor);
                }
            }

            if($scope.as.as_as_incluso_comercial_rcfdc){
                if(typeof($scope.as.as_as_incluso_comercial_rcfdc_valor) != 'undefined'){
                    $scope.as.as_valor_total_bruto += parseFloat($scope.as.as_as_incluso_comercial_rcfdc_valor);
                }
            }

            if($scope.as.as_as_incluso_comercial_rctrc){
                if(typeof($scope.as.as_as_incluso_comercial_rctrc_valor) != 'undefined'){
                    $scope.as.as_valor_total_bruto += parseFloat($scope.as.as_as_incluso_comercial_rctrc_valor);
                }
            }

            if($scope.as.as_as_incluso_comercial_despacho){
                if(typeof($scope.as.as_as_incluso_comercial_despacho_valor) != 'undefined'){
                    $scope.as.as_valor_total_bruto += parseFloat($scope.as.as_as_incluso_comercial_despacho_valor);
                }
            }

            if($scope.as.as_as_incluso_comercial_carga){
                if(typeof($scope.as.as_as_incluso_comercial_carga_valor) != 'undefined'){
                    $scope.as.as_valor_total_bruto += parseFloat($scope.as.as_as_incluso_comercial_carga_valor);
                }
            }

            if($scope.as.as_as_incluso_comercial_descarga){
                if(typeof($scope.as.as_as_incluso_comercial_descarga_valor) != 'undefined'){
                    $scope.as.as_valor_total_bruto += parseFloat($scope.as.as_as_incluso_comercial_descarga_valor);
                }
            }

            if($scope.as.as_as_incluso_comercial_estadia){
                if(typeof($scope.as.as_as_incluso_comercial_estadia_valor) != 'undefined'){
                    $scope.as.as_valor_total_bruto += parseFloat($scope.as.as_as_incluso_comercial_estadia_valor);
                }
            }

            if($scope.as.as_as_incluso_comercial_pernoite){
                if(typeof($scope.as.as_as_incluso_comercial_pernoite_valor) != 'undefined'){
                    $scope.as.as_valor_total_bruto += parseFloat($scope.as.as_as_incluso_comercial_pernoite_valor);
                }
            }

            if($scope.as.as_as_incluso_comercial_armazenagem){
                if(typeof($scope.as.as_as_incluso_comercial_armazenagem_valor) != 'undefined'){
                    $scope.as.as_valor_total_bruto += parseFloat($scope.as.as_as_incluso_comercial_armazenagem_valor);
                }
            }

            if($scope.as.as_as_incluso_comercial_advalorem){
                if(typeof($scope.as.as_as_incluso_comercial_advalorem_valor) != 'undefined'){
                    $scope.as.as_valor_total_bruto += parseFloat($scope.as.as_as_incluso_comercial_advalorem_valor);
                }
            }

            // Valor liquido a receber

            $scope.as.as_as_incluso_contabil_valor_retido = 0;

            $scope.as.as_valor_liquido_receber = $scope.as.as_valor_total_bruto;
           
            if($scope.as.as_as_incluso_contabil_iss && $scope.as.as_as_incluso_contabil_iss_retido){
                $scope.as.as_valor_liquido_receber -= parseFloat($scope.as.as_as_incluso_contabil_iss_valor);
                $scope.as.as_as_incluso_contabil_valor_retido += parseFloat($scope.as.as_as_incluso_contabil_iss_valor);
            }

            if($scope.as.as_as_incluso_contabil_inss && $scope.as.as_as_incluso_contabil_inss_retido){
                $scope.as.as_valor_liquido_receber -= parseFloat($scope.as.as_as_incluso_contabil_inss_valor);
                $scope.as.as_as_incluso_contabil_valor_retido += parseFloat($scope.as.as_as_incluso_contabil_inss_valor);
            }

            if($scope.as.as_as_incluso_contabil_ir && $scope.as.as_as_incluso_contabil_ir_retido){
                $scope.as.as_valor_liquido_receber -= parseFloat($scope.as.as_as_incluso_contabil_ir_valor);
                $scope.as.as_as_incluso_contabil_valor_retido += parseFloat($scope.as.as_as_incluso_contabil_ir_valor);
            }

            if($scope.as.as_as_incluso_contabil_pis && $scope.as.as_as_incluso_contabil_pis_retido){
                $scope.as.as_valor_liquido_receber -= parseFloat($scope.as.as_as_incluso_contabil_pis_valor);
                $scope.as.as_as_incluso_contabil_valor_retido += parseFloat($scope.as.as_as_incluso_contabil_pis_valor);
            }

            if($scope.as.as_as_incluso_contabil_cofins && $scope.as.as_as_incluso_contabil_cofins_retido){
                $scope.as.as_valor_liquido_receber -= parseFloat($scope.as.as_as_incluso_contabil_cofins_valor);
                $scope.as.as_as_incluso_contabil_valor_retido += parseFloat($scope.as.as_as_incluso_contabil_cofins_valor);
            }

            if($scope.as.as_as_incluso_contabil_csll && $scope.as.as_as_incluso_contabil_csll_retido){
                $scope.as.as_valor_liquido_receber -= parseFloat($scope.as.as_as_incluso_contabil_csll_valor);
                $scope.as.as_as_incluso_contabil_valor_retido += parseFloat($scope.as.as_as_incluso_contabil_csll_valor);
            }

            // ---- Resultado bruto

            $scope.as.as_valor_resultado_bruto = $scope.as.as_valor_liquido_receber;

            if($scope.as.as_as_incluso_contabil_iss && !$scope.as.as_as_incluso_contabil_iss_retido){
                $scope.as.as_valor_resultado_bruto -= parseFloat($scope.as.as_as_incluso_contabil_iss_valor);
            }

            if($scope.as.as_as_incluso_contabil_inss && !$scope.as.as_as_incluso_contabil_inss_retido){
                $scope.as.as_valor_resultado_bruto -= parseFloat($scope.as.as_as_incluso_contabil_inss_valor);
            }

            if($scope.as.as_as_incluso_contabil_ir && !$scope.as.as_as_incluso_contabil_ir_retido){
                $scope.as.as_valor_resultado_bruto -= parseFloat($scope.as.as_as_incluso_contabil_ir_valor);
            }

            if($scope.as.as_as_incluso_contabil_pis && !$scope.as.as_as_incluso_contabil_pis_retido){
                $scope.as.as_valor_resultado_bruto -= parseFloat($scope.as.as_as_incluso_contabil_pis_valor);
            }

            if($scope.as.as_as_incluso_contabil_cofins && !$scope.as.as_as_incluso_contabil_cofins_retido){
                $scope.as.as_valor_resultado_bruto -= parseFloat($scope.as.as_as_incluso_contabil_cofins_valor);
            }

            if($scope.as.as_as_incluso_contabil_csll && !$scope.as.as_as_incluso_contabil_csll_retido){
                $scope.as.as_valor_resultado_bruto -= parseFloat($scope.as.as_as_incluso_contabil_csll_valor);
            }

            if($scope.as.as_as_incluso_contabil_cp){
                $scope.as.as_valor_resultado_bruto -= parseFloat($scope.as.as_as_incluso_contabil_cp_valor);
            }

            // Porcentagem Ad Valores
            if($scope.as.as_as_incluso_comercial_advalorem_percent){
                $scope.as.as_as_incluso_comercial_advalorem_valor = ($scope.total_mercadoria_as_objetos * parseFloat($scope.as.as_as_incluso_comercial_advalorem_percent)) / 100;
            }

            if($scope.as.as_as_incluso_comercial_rcfdc_percent){
                $scope.as.as_as_incluso_comercial_rcfdc_valor = ($scope.total_mercadoria_as_objetos * parseFloat($scope.as.as_as_incluso_comercial_rcfdc_percent)) / 100;
            }

            if($scope.as.as_as_incluso_comercial_rctrc_percent){
                $scope.as.as_as_incluso_comercial_rctrc_valor = ($scope.total_mercadoria_as_objetos * parseFloat($scope.as.as_as_incluso_comercial_rctrc_percent)) / 100;
            }

            if($scope.as.as_as_incluso_comercial_icms_percent){
                $scope.as.as_as_incluso_comercial_icms_valor = ($scope.as.as_valor_total_bruto * parseFloat($scope.as.as_as_incluso_comercial_icms_percent)) / 100;
            }

            if($scope.as.as_as_incluso_comercial_iss_percent){
                $scope.as.as_as_incluso_comercial_iss_valor = ($scope.as.as_valor_total_bruto * parseFloat($scope.as.as_as_incluso_comercial_iss_percent)) / 100;
            }

            // Porcentagem valores fiscais
            $scope.as.as_as_incluso_contabil_icms_valor = ($scope.as.as_valor_total_bruto * parseFloat($scope.as.as_as_incluso_contabil_icms_percent)) / 100;

            $scope.as.as_as_incluso_contabil_iss_valor = ($scope.as.as_valor_total_bruto * parseFloat($scope.as.as_as_incluso_contabil_iss_percent)) / 100;

            $scope.as.as_as_incluso_contabil_inss_valor = ( ( ($scope.as.as_valor_total_bruto * parseFloat($scope.as.as_as_incluso_contabil_inss_percent_root)) / 100) * parseFloat($scope.as.as_as_incluso_contabil_inss_percent)) / 100;

            $scope.as.as_as_incluso_contabil_ir_valor = ($scope.as.as_valor_total_bruto * parseFloat($scope.as.as_as_incluso_contabil_ir_percent)) / 100;

            $scope.as.as_as_incluso_contabil_pis_valor = ($scope.as.as_valor_total_bruto * parseFloat($scope.as.as_as_incluso_contabil_pis_percent)) / 100;

            $scope.as.as_as_incluso_contabil_cofins_valor = ($scope.as.as_valor_total_bruto * parseFloat($scope.as.as_as_incluso_contabil_cofins_percent)) / 100;

            $scope.as.as_as_incluso_contabil_csll_valor = ($scope.as.as_valor_total_bruto * parseFloat($scope.as.as_as_incluso_contabil_csll_percent)) / 100;

            $scope.as.as_as_incluso_contabil_cp_valor = ($scope.as.as_valor_total_bruto * parseFloat($scope.as.as_as_incluso_contabil_cp_percent)) / 100;


            $scope.as.as_valor_resultado_liquido = $scope.as.as_valor_resultado_bruto;


            // Valores finais
            $scope.as.as_fat_valor_total_as_bruto = $scope.as.as_valor_total_bruto;
            $scope.as.as_fat_valor_total_as_liquido = $scope.as.as_valor_liquido_receber;

        }

        $scope.$watch('as', function() {
            calc_total_as_objeto();
        }, true);

        // Dados da carga
        $scope.open_carga_form = function(){
            $scope.carga = {
                is_edit : false
            };
            $rootScope.openModal("/app/components/autorizacao-de-servico/dados-carga-form.modal.html",false,$scope);
        }

        $scope.save_as_carga = function(){

            if(!$scope.carga.is_edit){
                $scope.as.as_dados_carga.push($scope.carga)
            }else{
                $scope.as.as_dados_carga[$scope.carga.key] = $scope.carga;
            }

            $scope.carga = {}
            $rootScope.closeModal();

            ngToast.create({
                className: 'success',
                content: 'Carga incluída com sucesso!'
            });

        }

        $scope.edit_carga = function(key){
            $scope.carga = $scope.as.as_dados_carga[key];
            $scope.carga.is_edit = true;
            $scope.carga.key = key;
            $rootScope.openModal("/app/components/autorizacao-de-servico/dados-carga-form.modal.html",false,$scope);
        }

        $scope.remove_carga = function(key){
            if(confirm("Deseja remover essa carga?")){
                $scope.as.as_dados_carga.splice(key, 1);
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

        function get_prazos_pg(){

            $http.get('/api/public/autorizacao-servico-prazo-pg/get?getall=1').then(function (response) {
                $scope.prazos_pgs = response.data.results;
            });

        }

        function get_prazo_razoes(){

            $http.get('/api/public/prazo-razoes/get?getall=1').then(function (response) {
                $scope.prazo_razoes = response.data.results;
            });

        }

        function get_taxas_tipos(){

            $http.get('/api/public/taxas-licencas/tipos/get?getall=1').then(function (response) {
                $scope.taxas_tipos = response.data.results;
            });

        }

        function get_taxas_categorias(){

            $http.get('/api/public/taxas-licencas/categorias/get?getall=1').then(function (response) {
                $scope.taxas_categorias = response.data.results;
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

        $scope.veiculo_search_filter = {}
        $scope.veiculo_is_search = false;

        $scope.open_choose_veiculos = function(fn){
            $scope.open_choose_veiculo_fn = fn;
            $rootScope.openModal("/app/components/autorizacao-de-servico/veiculos.modal.html",false,$scope);
        }

        $scope.choose_veiculo = function(veiculo){
            $scope[$scope.open_choose_veiculo_fn](veiculo);
            $rootScope.closeModal();
            $scope.veiculo_is_search = false;
        }

        $scope.assoc_frota = function(veiculo){
            $scope.as.as_assoc_frotas.push(veiculo);
        }

        $scope.remove_veiculo = function(key){
            if(confirm("Deseja remover esse veículo?")){
                $scope.as.as_assoc_frotas.splice(key, 1);
            }
        }

        $scope.search_modal_veiculo = function(){
            $rootScope.is_modal_loading = true;

            $http.get('/api/public/veiculos/search?term=' + $scope.veiculo_search_filter.free_term, $scope.as ).then(function (response) {
                    
                if(response.data.result){
                    $scope.veiculos = response.data.results;
                    $scope.veiculo_is_search = true;
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

        // Altera os status das formas de faturamento
        $scope.$watch('as.as_fat_forma_faturamento', function() {
            $scope.as.as_as_forma_faturamento = $scope.as.as_fat_forma_faturamento;

            if($scope.as.as_as_forma_faturamento == 'cte'){
                $scope.as.as_as_incluso_contabil_icms = true;
            }

        });

        $scope.$watch('as.as_as_forma_faturamento', function() {
            $scope.as.as_fat_forma_faturamento = $scope.as.as_as_forma_faturamento;
        });

        $scope.$watch('as.as_fat_forma_pagamento', function() {
            $scope.as.as_as_forma_pagamento = $scope.as.as_fat_forma_pagamento;

            if($scope.as.as_as_forma_faturamento == 'cte'){
                $scope.as.as_as_incluso_contabil_icms = true;
            }
            
        });

        $scope.$watch('as.as_as_forma_pagamento', function() {
            $scope.as.as_fat_forma_pagamento = $scope.as.as_as_forma_pagamento;
        });

        // Taxas e licenças

        function get_taxa_code(){

            var code = String(  ($scope.as.as_taxas_licencas.length) + 1) .padStart(6, '0');

            if($scope.as.as_taxas_licencas.length > 0){ 
                var max_taxa = $scope.as.as_taxas_licencas[$scope.as.as_taxas_licencas.length - 1];
                code = String( parseInt(max_taxa.taxa_num_sequencial) + 1) .padStart(6, '0'); 
            }   

            return code;

        }

        $scope.open_insert_taxa = function(fn){

            var taxa_num_sequencial = get_taxa_code();

            $scope.taxa = {
                is_edit : false,
                taxa_status : 'pendente',
                taxa_arquivos : [],
                id_tipo : '0',
                id_categoria : '0',
                taxa_code : 'PROV-' + taxa_num_sequencial,
                taxa_num_sequencial : parseInt(taxa_num_sequencial),
                taxa_previsao_pagamento_obj : new Date()
            };
            $rootScope.openModal("/app/components/autorizacao-de-servico/taxas-licencas.modal.html",false,$scope);
        }

        $scope.save_file_to_taxa = function(args){
            args = $.parseJSON(args);

            if(args.result){
                $scope.taxa.taxa_arquivos.push(args.data);
            }
        }

        $scope.remove_file_taxa = function(key){
            if(confirm("Deseja remover esse arquivo?")){
                $scope.taxa.taxa_arquivos.splice(key, 1);
            }
        }

        function get_taxa_tipo_text(id){
            if(id != '0'){
                $.each($scope.taxas_tipos,function(key, val){
                    if(val.id == id){
                        return val.tipo_nome;
                    }
                });
            }
        }

        $scope.save_as_taxa = function(){
            $scope.taxa.taxa_previsao_pagamento = Math.floor($scope.taxa.taxa_previsao_pagamento_obj.getTime() / 1000);
            $scope.taxa.taxa_tipo_text = $('#id_tipo option:checked').html();

            console.log($scope.taxa.taxa_tipo_text)

            if($scope.taxa.is_edit){
                $scope.as.as_taxas_licencas[$scope.taxa.key] = $scope.taxa;
            }else{
                $scope.as.as_taxas_licencas.push($scope.taxa);
            }
            
            $rootScope.closeModal();
        }

        $scope.as_taxas_licencas_total = 0;

        $scope.$watch('as.as_taxas_licencas', function() {
            $scope.as_taxas_licencas_total = 0;
            $.each($scope.as.as_taxas_licencas, function(key, val){
                $scope.as_taxas_licencas_total += parseFloat(val.taxa_valor);
            });
        }, true);

        $scope.edit_taxa = function(key){
            $scope.taxa = $scope.as.as_taxas_licencas[key];
            $scope.taxa.taxa_previsao_pagamento_obj = new Date($scope.taxa.taxa_previsao_pagamento * 1000);
            $scope.taxa.is_edit = true;
            $scope.taxa.key = key;
            $rootScope.openModal("/app/components/autorizacao-de-servico/taxas-licencas.modal.html",false,$scope);
        }

        $scope.remove_taxa = function(key){
            if(confirm("Deseja remover essa taxa?")){
                $scope.as.as_taxas_licencas.splice(key, 1);
            }
        }

        //CFOP

        $scope.cfop_data = false;
        $scope.cfop_is_search = false;
        $scope.cfop_search_filter = { free_term : '' };

        $scope.open_choose_cfop = function(fn){
            $scope.open_choose_cfop_fn = fn;
            $rootScope.openModal("/app/components/autorizacao-de-servico/cfop.modal.html",false,$scope);
        }

        $scope.choose_cfop = function(cliente){
            $scope[$scope.open_choose_cfop_fn](cliente);
            $rootScope.closeModal();
            $scope.cfop_is_search = false;
        }

        $scope.operacional_cfop = function(cfop){
            $scope.cfop_data = cfop;
            $scope.cfop_data.full_name = cfop.cfop_codigo + ' - ' + cfop.cfop_descricao;
            $scope.as.as_op_cfop_id = cfop.id;
            console.log(cfop)
        }

        $scope.search_modal_cfop = function(){
            $rootScope.is_modal_loading = true;
            
            $http.get('/api/public/cfop/search?term=' + $scope.cfop_search_filter.free_term).then(function (response) {
                
                if(response.data.result){
                    $scope.cfops = response.data.results;
                    $scope.cfop_is_search = true;
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


        // Motoristas

        $scope.motorista_search_filter = {}
        $scope.motorista_is_search = false;

        $scope.open_choose_motoristas = function(fn){
            $scope.open_choose_motorista_fn = fn;
            $rootScope.openModal("/app/components/autorizacao-de-servico/motoristas.modal.html",false,$scope);
        }

        $scope.choose_motorista = function(veiculo){
            $scope[$scope.open_choose_motorista_fn](veiculo);
            $rootScope.closeModal();
            $scope.motorista_is_search = false;
        }

        $scope.assoc_motorista = function(motorista){
            $scope.as.as_assoc_motoristas.push(motorista);
        }

        $scope.remove_motorista = function(key){
            if(confirm("Deseja remover esse motorista?")){
                $scope.as.as_assoc_motoristas.splice(key, 1);
            }
        }

        $scope.search_modal_motorista = function(){
            $rootScope.is_modal_loading = true;

            $http.get('/api/public/motoristas/search?term=' + $scope.motorista_search_filter.free_term ).then(function (response) {
                    
                if(response.data.result){
                    $scope.motoristas = response.data.results;
                    $scope.motorista_is_search = true;
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

        
    }

})();