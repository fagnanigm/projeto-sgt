(function () {
    'use strict';

    angular
        .module('app')
        .controller('Cotacoes.GerenciaController', Controller);

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
                    cotacao_cadastro_data_obj : new Date(),
                    cotacao_anexos_objetos : [],
                    cotacao_condicoes_pagamento_id : '0',
                    cotacao_validade_proposta_id : '0',
                    cotacao_prazo_razao_id : '0',
                    cotacao_previsao_mes : '0',
                    cotacao_vi_pedagios : true,
                    cotacao_vi_escolta : true,
                    cotacao_vi_taxas : true,
                    cotacao_equipamentos_tipos : [],
                    cotacao_modelo_impressao: 'modelo-1'
                }

                var copy = $location.search().copy;

                if(copy){

                    $http.get('/api/public/cotacoes/get/' + copy ).then(function (response) {

                        $scope.cotacao = response.data.cotacao;

                        delete $scope.cotacao.id;
                        delete $scope.cotacao.id_revisao;

                        $scope.cotacao.cotacao_status = '0';
                        $scope.cotacao.cotacao_revisao = '0';
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

            }else{

                $http.get('/api/public/cotacoes/get/' + $rootScope.$stateParams.id_cotacao ).then(function (response) {

                    $scope.cotacao = response.data.cotacao;

                    if($scope.cotacao.cotacao_status == 'aprovado'){

                        ngToast.create({
                            className: 'danger',
                            content: "Operação não permitida, cotação já aprovada."
                        });

                        setTimeout(function(){
                            location.href = '/cotacoes';
                        },2000)
                        
                    }

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
            get_prazos_pg();
            get_prazo_razoes();
            get_validades_proposta();
            get_textos_padroes();
            get_equip_tipos_comerciais();
            $rootScope.get_ufs();
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

            // Verifica se todos os equipamentos estão com o tipo selecionado
            $.each($scope.cotacao.cotacao_equipamentos_tipos, function(key, val){

                if(val.ce_tipo_comercial_id == '0'){
                    ngToast.create({
                        className: 'danger',
                        content: "Selecione o tipo comercial de todos os equipamentos."
                    });
                    error++;
                    return;
                }

            });

            if(error == 0){

                $rootScope.is_loading = true;

                $scope.cotacao.cotacao_cadastro_data = Math.floor($scope.cotacao.cotacao_cadastro_data_obj.getTime() / 1000);

                $http.post('/api/public/cotacoes/insert', $scope.cotacao).then(function (response) {
                    
                    if(response.data.result){

                        $scope.allow_change_page = true;

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

                if($rootScope.$state.name == "insert-cotacao"){
                    $rootScope.is_loading = false;
                }
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

        function get_prazo_razoes(){

            $http.get('/api/public/prazo-razoes/get?getall=1').then(function (response) {
                $scope.prazo_razoes = response.data.results;
            });

        }

        function get_textos_padroes(){

            var rest_address = '/api/public/textos-padroes/get?keys=cotacao_objeto_operacao,cotacao_carga_descarga,cotacao_carencia,cotacao_prazo_execucao,cotacao_observacoes_finais';

            $http.get(rest_address).then(function (response) {
                $.each(response.data.results, function(key, val){
                    $scope.cotacao[key] = val.texto_descricao;
                });
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
                    console.log($scope.clientes)
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
                is_edit : false,
                objeto_item : ($scope.cotacao.cotacao_caracteristica_objetos.length + 1)
            };

            $scope.$watch('[objeto.objeto_quantidade, objeto.objeto_valor_unit]', function() {
                var quantidade = parseInt($scope.objeto.objeto_quantidade, 10);
                quantidade = (!Number.isNaN(quantidade) ? quantidade : 1 );
                $scope.objeto.objeto_valor_total = quantidade * $scope.objeto.objeto_valor_unit;
            }, true);

            $scope.$watch('objeto.objeto_local_servico_cidade',function(){
                var localidade_id = $('select[name="local_cidade"] option:selected').data('localidade-id');
                if(typeof(localidade_id) != 'undefined'){
                    $scope.objeto.objeto_local_servico_id = localidade_id;
                }
            });

            $scope.$watch('objeto.objeto_origem_cidade',function(){
                var localidade_id = $('select[name="objeto_origem_cidade"] option:selected').data('localidade-id');
                if(typeof(localidade_id) != 'undefined'){
                    $scope.objeto.objeto_origem_id = localidade_id;
                }
            });

            $scope.$watch('objeto.objeto_destino_cidade',function(){
                var localidade_id = $('select[name="objeto_destino_cidade"] option:selected').data('localidade-id');
                if(typeof(localidade_id) != 'undefined'){
                    $scope.objeto.objeto_destino_id = localidade_id;
                }
            });

            $rootScope.openModal("/app/components/cotacoes/objeto-form.modal.html",false,$scope);
        }

        $scope.save_cotacao_objeto = function(){

            if(!$scope.objeto.is_edit){
                $scope.cotacao.cotacao_caracteristica_objetos.push($scope.objeto);

                // Adiciona o objeto nos itens de equipamento / tipos comerciais
                $scope.cotacao.cotacao_equipamentos_tipos.push({
                    ce_descricao : $scope.objeto.objeto_descricao,
                    ce_tipo_comercial_id : '0'
                });

            }else{
                $scope.cotacao.cotacao_caracteristica_objetos[$scope.objeto.key] = $scope.objeto;
            }

            console.log($scope.objeto);
            
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

            $scope.$watch('[objeto.objeto_quantidade, objeto.objeto_valor_unit]', function() {
                var quantidade = parseInt($scope.objeto.objeto_quantidade, 10);
                quantidade = (!Number.isNaN(quantidade) ? quantidade : 1 );
                $scope.objeto.objeto_valor_total = quantidade * $scope.objeto.objeto_valor_unit;
            }, true);

            $scope.$watch('objeto.objeto_local_servico_cidade',function(){
                var localidade_id = $('select[name="local_cidade"] option:selected').data('localidade-id');
                if(typeof(localidade_id) != 'undefined'){
                    $scope.objeto.objeto_local_servico_id = localidade_id;
                }
            });

            $scope.$watch('objeto.objeto_origem_cidade',function(){
                var localidade_id = $('select[name="objeto_origem_cidade"] option:selected').data('localidade-id');
                if(typeof(localidade_id) != 'undefined'){
                    $scope.objeto.objeto_origem_id = localidade_id;
                }
            });

            $scope.$watch('objeto.objeto_destino_cidade',function(){
                var localidade_id = $('select[name="objeto_destino_cidade"] option:selected').data('localidade-id');
                if(typeof(localidade_id) != 'undefined'){
                    $scope.objeto.objeto_destino_id = localidade_id;
                }
            });

            if($scope.objeto.objeto_local_servico_cidade.length > 0){
                $rootScope.ufChange($scope.objeto.objeto_local_servico_uf);
            }

            if($scope.objeto.objeto_origem_cidade.length > 0){
                $scope.uf_origem_change($scope.objeto.objeto_origem_uf)
            }

            if($scope.objeto.objeto_destino_cidade.length > 0){
                $scope.uf_destino_change($scope.objeto.objeto_destino_uf)
            }

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

        // Files 

        $scope.save_to_cotacao = function(args){

            args = $.parseJSON(args);

            if(args.result){
                $scope.cotacao.cotacao_anexos_objetos.push(args.data);
            }

        }

        // Aprovação

        $scope.approve_cotacao = function(){

            if(confirm("Deseja aprovar essa cotação?")){

                $rootScope.is_loading = true;

                $http.post('/api/public/cotacoes/approve', { id : $scope.cotacao.id }).then(function (response) {
                
                    if(response.data.result){

                        $scope.allow_change_page = true;

                        ngToast.create({
                            className: 'success',
                            content: 'Cotação aprovada com sucesso! Redirecionando...'
                        });

                        $location.path('/projetos/visualizar/' + response.data.id);
                        
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

        function get_prazos_pg(){

            $http.get('/api/public/autorizacao-servico-prazo-pg/get?getall=1').then(function (response) {
                $scope.prazos_pgs = response.data.results;
            });

        }

        function get_validades_proposta(){

            $http.get('/api/public/validades-proposta/get?getall=1').then(function (response) {
                $scope.validades_propostas = response.data.results;
            });

        }

        function get_equip_tipos_comerciais(){

            $http.get('/api/public/equipamentos-tipos-comerciais/get?getall=1').then(function (response) {
                $scope.equip_tipos_comerciais = response.data.results;
            });

        }

        $scope.equipamentos_tipos_add = function(){

            $scope.cotacao.cotacao_equipamentos_tipos.push({
                ce_descricao : '',
                ce_tipo_comercial_id : '0'
            });

        };

        $scope.equipamentos_tipos_remove = function(key){
            if(confirm("Deseja remover esse equipamento?")){
                $scope.cotacao.cotacao_equipamentos_tipos.splice(key, 1);
            }
        };


        // Origem destino

        $scope.uf_origem_change = function(term){
            $rootScope.is_modal_loading = true;
            $http.get('/api/public/localidades/get/municipios/'+term).then(function (response) {
                $rootScope.origem_municipios = response.data.municipios;
                $rootScope.is_modal_loading = false;
            });
        }

        $scope.uf_destino_change = function(term){
            $rootScope.is_modal_loading = true;
            $http.get('/api/public/localidades/get/municipios/'+term).then(function (response) {
                $rootScope.destino_municipios = response.data.municipios;
                $rootScope.is_modal_loading = false;
            });
        }
         
    }

})();