(function () {
    'use strict';

    angular
        .module('app')
        .controller('Relatorios.IndexController', Controller);

    function Controller($scope, $rootScope, $http, ngToast) {
        var vm = this;

        $('#tabs-relatorios a').on('click', function (e) {
            e.preventDefault()
            $(this).tab('show')
        });

        initController();

        function initController() {
            $rootScope.is_loading = false;
            get_categorias();
            get_vendedores();
            $rootScope.get_ufs();
        }

        function get_categorias(){
            $http.get('/api/public/categorias/get?getall=1').then(function (response) {
                $scope.categorias = response.data.results;
            });
        }

        function get_vendedores(){
            $http.get('/api/public/vendedores/get?getall=1').then(function (response) {
                $scope.vendedores = response.data.results;
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
            $rootScope.openModal("/app/components/relatorios/clientes.modal.html",false,$scope);
        }

        $scope.choose_cliente = function(cliente){
            $scope[$scope.open_choose_cliente_fn](cliente);
            $rootScope.closeModal();
            $scope.cliente_is_search = false;
        }

        // Pesquisa de projetos

        $scope.projeto_search_filter = { free_term : '' };
        $scope.projeto_is_search = false;

        $scope.search_modal_projeto = function(){
            $rootScope.is_modal_loading = true;
            
            $http.get('/api/public/projetos/search?term=' + $scope.projeto_search_filter.free_term).then(function (response) {
                
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

        $scope.open_choose_projetos = function(fn){
            $scope.open_choose_projeto_fn = fn;
            $rootScope.openModal("/app/components/relatorios/projetos.modal.html",false,$scope);
        }

        $scope.choose_projeto = function(projeto){
            $scope[$scope.open_choose_projeto_fn](projeto);
            $rootScope.closeModal();
            $scope.projeto_is_search = false;
        }

        // Relatório de COTAÇÃO POR STATUS
        $scope.init_cotacao_status = function(){

            var date = new Date();

            $scope.cotacao_status_filter = {
                status : '0',
                init : new Date(date.getFullYear(), date.getMonth(), 1),
                end : new Date(),
            }

        }

        $scope.submit_cotacao_status = function(){

            var filter = {
                cotacao_status: $scope.cotacao_status_filter.status,
                init: Math.floor($scope.cotacao_status_filter.init.getTime() / 1000),
                end: Math.floor($scope.cotacao_status_filter.end.getTime() / 1000),
            }

            if(filter.cotacao_status == '0'){
                ngToast.create({
                    className: 'danger',
                    content: "Selecione um status para a emissão desse relatório."
                });
                return;
            }

            $rootScope.is_loading = true;

            $http.post('/api/public/relatorios/cotacao/status', filter).then(function (response) {

                console.log(response);

                $rootScope.is_loading = false;

                if(response.data.result){
                    
                    window.open('/api/public' + response.data.file, '_blank');

                }else{
                    ngToast.create({
                        className: 'danger',
                        content: response.data.error
                    });
                }

            });

        }


        // Relatório de COTAÇÃO POR VENDEDORES
        $scope.init_cotacao_vendedores = function(){

            var date = new Date();

            $scope.cotacao_vendedores_filter = {
                id_vendedor : '0',
                init : new Date(date.getFullYear(), date.getMonth(), 1),
                end : new Date(),
            }

        }

        $scope.submit_cotacao_vendedores = function(){

            var filter = {
                id_vendedor: $scope.cotacao_vendedores_filter.id_vendedor,
                init: Math.floor($scope.cotacao_vendedores_filter.init.getTime() / 1000),
                end: Math.floor($scope.cotacao_vendedores_filter.end.getTime() / 1000),
            }

            if(filter.id_vendedor == '0'){
                ngToast.create({
                    className: 'danger',
                    content: "Selecione um vendedor para a emissão desse relatório."
                });
                return;
            }

            $rootScope.is_loading = true;

            $http.post('/api/public/relatorios/cotacao/vendedores', filter).then(function (response) {

                console.log(response);

                $rootScope.is_loading = false;

                if(response.data.result){
                    
                    window.open('/api/public' + response.data.file, '_blank');

                }else{
                    ngToast.create({
                        className: 'danger',
                        content: response.data.error
                    });
                }

            });

        }


        // Relatório de COTAÇÃO POR PERÍODO
        $scope.init_cotacao_periodo = function(){

            var date = new Date();

            $scope.cotacao_periodo_filter = {
                init : new Date(date.getFullYear(), date.getMonth(), 1),
                end : new Date()
            }

        }

        $scope.submit_cotacao_periodo = function(){

            var filter = {
                init: Math.floor($scope.cotacao_periodo_filter.init.getTime() / 1000),
                end: Math.floor($scope.cotacao_periodo_filter.end.getTime() / 1000)
            }

            $rootScope.is_loading = true;

            $http.post('/api/public/relatorios/cotacao/periodo', filter).then(function (response) {

                console.log(response);

                $rootScope.is_loading = false;

                if(response.data.result){
                    
                    window.open('/api/public' + response.data.file, '_blank');

                }else{
                    ngToast.create({
                        className: 'danger',
                        content: response.data.error
                    });
                }

            });

        }


        // Relatório de COTAÇÃO POR CLIENTE
        $scope.init_cotacao_cliente = function(){

            var date = new Date();

            $scope.cotacao_cliente_filter = {
                init : new Date(date.getFullYear(), date.getMonth(), 1),
                end : new Date(),
                id_cliente: false
            }

        }

        $scope.submit_cotacao_cliente = function(){

            var filter = {
                id_cliente: $scope.cotacao_cliente_filter.id_cliente,
                init: Math.floor($scope.cotacao_cliente_filter.init.getTime() / 1000),
                end: Math.floor($scope.cotacao_cliente_filter.end.getTime() / 1000)
            }

            if(filter.id_cliente == false){
                ngToast.create({
                    className: 'danger',
                    content: "Selecione um cliente para a emissão desse relatório."
                });
                return;
            }

            $rootScope.is_loading = true;

            $http.post('/api/public/relatorios/cotacao/cliente', filter).then(function (response) {

                console.log(response);

                $rootScope.is_loading = false;

                if(response.data.result){
                    
                    window.open('/api/public' + response.data.file, '_blank');

                }else{
                    ngToast.create({
                        className: 'danger',
                        content: response.data.error
                    });
                }

            });

        }

        $scope.set_cotacao_cliente = function(cliente){
            $scope.cotacao_cliente_filter.cliente_nome = cliente.cliente_nome;
            $scope.cotacao_cliente_filter.id_cliente = cliente.id;
        }

        // Relatório de COTAÇÃO POR DESTINO / ORIGEM
        $scope.init_cotacao_destino_origem = function(){

            var date = new Date();

            $scope.cotacao_destino_filter = {
                init : new Date(date.getFullYear(), date.getMonth(), 1),
                end : new Date(),
                origem: '0',
                destino: '0'
            }

        }

        $scope.submit_cotacao_destino_origem = function(){

            var filter = {
                init: Math.floor($scope.cotacao_destino_filter.init.getTime() / 1000),
                end: Math.floor($scope.cotacao_destino_filter.end.getTime() / 1000),
                origem: $scope.cotacao_destino_filter.origem,
                destino: $scope.cotacao_destino_filter.destino,
            }

            if(filter.origem == '0' && filter.destino == '0'){
                ngToast.create({
                    className: 'danger',
                    content: "Selecione um destino ou uma origem para a emissão desse relatório."
                });
                return;
            }
                
            $rootScope.is_loading = true;

            $http.post('/api/public/relatorios/cotacao/origem-destino', filter).then(function (response) {

                console.log(response);

                $rootScope.is_loading = false;

                if(response.data.result){
                    
                    window.open('/api/public' + response.data.file, '_blank');

                }else{
                    ngToast.create({
                        className: 'danger',
                        content: response.data.error
                    });
                }

            });

        }


        // Relatório de COTAÇÃO POR DIMENSIONAL
        $scope.init_cotacao_dimensional = function(){

            var date = new Date();

            $scope.cotacao_dimensional_filter = {
                init: new Date(date.getFullYear(), date.getMonth(), 1),
                end: new Date(),
                altura: '',
                largura: '',
                comprimento: '',
                peso: ''
            }

        }

        $scope.submit_cotacao_dimensional = function(){

            var filter = {
                init: Math.floor($scope.cotacao_dimensional_filter.init.getTime() / 1000),
                end: Math.floor($scope.cotacao_dimensional_filter.end.getTime() / 1000),
                altura: $scope.cotacao_dimensional_filter.altura,
                largura: $scope.cotacao_dimensional_filter.largura,
                comprimento: $scope.cotacao_dimensional_filter.comprimento,
                peso: $scope.cotacao_dimensional_filter.peso
            }

            if(filter.altura.length == 0 && filter.largura.length == 0 && filter.comprimento.length == 0 && filter.peso.length == 0 ){
                ngToast.create({
                    className: 'danger',
                    content: "Preencha com pelo menos um dado para a emissão desse relatório."
                });
                return;
            }
                
            $rootScope.is_loading = true;

            $http.post('/api/public/relatorios/cotacao/dimensional', filter).then(function (response) {

                console.log(response);

                $rootScope.is_loading = false;

                if(response.data.result){
                    
                    window.open('/api/public' + response.data.file, '_blank');

                }else{
                    ngToast.create({
                        className: 'danger',
                        content: response.data.error
                    });
                }

            });

        }


        // Relatório de PROJETO POR CLIENTE
        $scope.init_projeto_cliente = function(){

            var date = new Date();

            $scope.projeto_cliente_filter = {
                init : new Date(date.getFullYear(), date.getMonth(), 1),
                end : new Date(),
                id_cliente: false
            }

        }

        $scope.submit_projeto_cliente = function(){

            var filter = {
                id_cliente: $scope.projeto_cliente_filter.id_cliente,
                init: Math.floor($scope.projeto_cliente_filter.init.getTime() / 1000),
                end: Math.floor($scope.projeto_cliente_filter.end.getTime() / 1000)
            }

            if(filter.id_cliente == false){
                ngToast.create({
                    className: 'danger',
                    content: "Selecione um cliente para a emissão desse relatório."
                });
                return;
            }

            $rootScope.is_loading = true;

            $http.post('/api/public/relatorios/projeto/cliente', filter).then(function (response) {

                console.log(response);

                $rootScope.is_loading = false;

                if(response.data.result){
                    
                    window.open('/api/public' + response.data.file, '_blank');

                }else{
                    ngToast.create({
                        className: 'danger',
                        content: response.data.error
                    });
                }

            });

        }

        $scope.set_projeto_cliente = function(cliente){
            $scope.projeto_cliente_filter.cliente_nome = cliente.cliente_nome;
            $scope.projeto_cliente_filter.id_cliente = cliente.id;
        }

        // Relatório de PROJETO POR PERÍODO
        $scope.init_projeto_periodo = function(){

            var date = new Date();

            $scope.projeto_periodo_filter = {
                init : new Date(date.getFullYear(), date.getMonth(), 1),
                end : new Date()
            }

        }

        $scope.submit_projeto_periodo = function(){

            var filter = {
                init: Math.floor($scope.projeto_periodo_filter.init.getTime() / 1000),
                end: Math.floor($scope.projeto_periodo_filter.end.getTime() / 1000)
            }

            $rootScope.is_loading = true;

            $http.post('/api/public/relatorios/projeto/periodo', filter).then(function (response) {

                console.log(response);

                $rootScope.is_loading = false;

                if(response.data.result){
                    
                    window.open('/api/public' + response.data.file, '_blank');

                }else{
                    ngToast.create({
                        className: 'danger',
                        content: response.data.error
                    });
                }

            });

        }

        // Relatório de PROJETO POR CATEGORIA
        $scope.init_projeto_categoria = function(){

            var date = new Date();

            $scope.projeto_categoria_filter = {
                init: new Date(date.getFullYear(), date.getMonth(), 1),
                end: new Date(),
                id_categoria: '0'
            }

        }

        $scope.submit_projeto_categoria = function(){

            var filter = {
                init: Math.floor($scope.projeto_categoria_filter.init.getTime() / 1000),
                end: Math.floor($scope.projeto_categoria_filter.end.getTime() / 1000),
                id_categoria: $scope.projeto_categoria_filter.id_categoria
            }

            if(filter.id_categoria == '0'){
                ngToast.create({
                    className: 'danger',
                    content: "Selecione uma categoria para a emissão desse relatório."
                });
                return;
            }

            $rootScope.is_loading = true;

            $http.post('/api/public/relatorios/projeto/categoria', filter).then(function (response) {

                console.log(response);

                $rootScope.is_loading = false;

                if(response.data.result){
                    
                    window.open('/api/public' + response.data.file, '_blank');

                }else{
                    ngToast.create({
                        className: 'danger',
                        content: response.data.error
                    });
                }

            });

        }


        // Relatório de AS POR CLIENTE
        $scope.init_as_cliente = function(){

            var date = new Date();

            $scope.as_cliente_filter = {
                init: new Date(date.getFullYear(), date.getMonth(), 1),
                end: new Date(),
                id_cliente: false
            }

        }

        $scope.submit_as_cliente = function(){

            var filter = {
                init: Math.floor($scope.as_cliente_filter.init.getTime() / 1000),
                end: Math.floor($scope.as_cliente_filter.end.getTime() / 1000),
                id_cliente: $scope.as_cliente_filter.id_cliente
            }

            if(filter.id_cliente == false){
                ngToast.create({
                    className: 'danger',
                    content: "Selecione um cliente para a emissão desse relatório."
                });
                return;
            }

            $rootScope.is_loading = true;

            $http.post('/api/public/relatorios/as/cliente', filter).then(function (response) {

                console.log(response);

                $rootScope.is_loading = false;

                if(response.data.result){
                    
                    window.open('/api/public' + response.data.file, '_blank');

                }else{
                    ngToast.create({
                        className: 'danger',
                        content: response.data.error
                    });
                }

            });

        }

        $scope.set_as_cliente = function(cliente){
            $scope.as_cliente_filter.cliente_nome = cliente.cliente_nome;
            $scope.as_cliente_filter.id_cliente = cliente.id;
        }


        // Relatório de AS POR PROJETO
        $scope.init_as_projeto = function(){

            var date = new Date();

            $scope.as_projeto_filter = {
                id_projeto: false
            }

        }

        $scope.submit_as_projeto = function(){

            var filter = {
                id_projeto: $scope.as_projeto_filter.id_projeto
            }

            if(filter.id_projeto == false){
                ngToast.create({
                    className: 'danger',
                    content: "Selecione um projeto para a emissão desse relatório."
                });
                return;
            }

            $rootScope.is_loading = true;

            $http.post('/api/public/relatorios/as/projeto', filter).then(function (response) {

                console.log(response);

                $rootScope.is_loading = false;

                if(response.data.result){
                    
                    window.open('/api/public' + response.data.file, '_blank');

                }else{
                    ngToast.create({
                        className: 'danger',
                        content: response.data.error
                    });
                }

            });

        }

        $scope.set_as_projeto = function(projeto){
            $scope.as_projeto_filter.projeto_code = projeto.projeto_code;
            $scope.as_projeto_filter.id_projeto = projeto.id
        }


    }

})();