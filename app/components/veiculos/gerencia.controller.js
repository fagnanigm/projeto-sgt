(function () {
    'use strict';

    angular
        .module('app')
        .controller('Veiculos.GerenciaController', Controller);

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

        $scope.veiculo = {};

        $scope.beneficios = [
            "Cama / Leito",
            "Aces. especial",
            "Cintas / Lonas / Encerado",
            "Cx. Ferramentas",
            "CTF",
            "Rastreador",
            "Telemetria",
            "Sem parar",
            "Kit ambiental"
        ];

        initController();

        function get_veiculo(){

            if($rootScope.$state.name == "insert-veiculo"){

                $scope.veiculo = {
                    id_author : $localStorage.currentUser.id,
                    id_filial : '0',
                    veiculo_person : 'proprio',
                    veiculo_beneficios_checked : [],
                    veiculo_tipo : '0',
                    veiculo_status : '0',
                    veiculo_ano_fab : '',
                    veiculo_ano_mod : '',
                    veiculo_st_financiamento : '0'
                }

            }else{

                $http.get('/api/public/veiculos/get/' + $rootScope.$stateParams.id_veiculo).then(function (response) {

                    $scope.veiculo = response.data.veiculo;

                    if($scope.veiculo.veiculo_data_quitacao.length > 0){
                        $scope.veiculo.veiculo_data_quitacao_obj = new Date($scope.veiculo.veiculo_data_quitacao_timestamp * 1000);
                    }

                    if($scope.veiculo.veiculo_venc_tacografo.length > 0){
                        $scope.veiculo.veiculo_venc_tacografo_obj = new Date($scope.veiculo.veiculo_venc_tacografo_timestamp * 1000);
                    }

                    if($scope.veiculo.veiculo_validade_antt.length > 0){
                        $scope.veiculo.veiculo_validade_antt_obj = new Date($scope.veiculo.veiculo_validade_antt_timestamp * 1000);
                    }

                    if($scope.veiculo.veiculo_vendido_data.length > 0){
                        $scope.veiculo.veiculo_vendido_data_obj = new Date($scope.veiculo.veiculo_vendido_data_timestamp * 1000);
                    }

                    if($scope.veiculo.veiculo_sinistro_data.length > 0){
                        $scope.veiculo.veiculo_sinistro_data_obj = new Date($scope.veiculo.veiculo_sinistro_data_timestamp * 1000);
                    }

                    var beneficios = $.parseJSON($scope.veiculo.veiculo_beneficios);
                    $scope.veiculo.veiculo_beneficios_checked = {};

                    $.each(beneficios, function(key, val){
                        $scope.veiculo.veiculo_beneficios_checked[val.key] = true;
                    });

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
            get_tipos();
        	get_veiculo();
        }


        vm.setVeiculo = function(){

            // Validação

            if($scope.veiculo.id_filial == '0'){
                ngToast.create({
                    className: 'danger',
                    content: "Escolha uma filial para este veículo"
                });
                return;
            }

            if($scope.veiculo.veiculo_tipo == '0'){
                ngToast.create({
                    className: 'danger',
                    content: "Escolha o tipo do veículo"
                });
                return;
            }

            if($scope.veiculo.veiculo_status == '0'){
                ngToast.create({
                    className: 'danger',
                    content: "Escolha o status do veículo"
                });
                return;
            }

            if($scope.veiculo.veiculo_st_financiamento == '0'){
                ngToast.create({
                    className: 'danger',
                    content: "Escolha o status do financiamento do veículo"
                });
                return;
            }

            // Tratamento 
            $scope.veiculo.veiculo_beneficios = [];

            $.each($scope.veiculo.veiculo_beneficios_checked, function(key, val){
                if(val){
                    $scope.veiculo.veiculo_beneficios.push({
                        key : key,
                        beneficio : $scope.beneficios[key]
                    })
                }
            });

            $scope.veiculo.veiculo_beneficios = JSON.stringify($scope.veiculo.veiculo_beneficios);

            if($scope.veiculo.veiculo_validade_antt_obj != null){
                $scope.veiculo.veiculo_validade_antt = Math.floor($scope.veiculo.veiculo_validade_antt_obj.getTime() / 1000);
            }

            if($scope.veiculo.veiculo_venc_tacografo_obj != null){
                $scope.veiculo.veiculo_venc_tacografo = Math.floor($scope.veiculo.veiculo_venc_tacografo_obj.getTime() / 1000);
            }

            if($scope.veiculo.veiculo_data_quitacao_obj != null){
                $scope.veiculo.veiculo_data_quitacao = Math.floor($scope.veiculo.veiculo_data_quitacao_obj.getTime() / 1000);
            }

            if($scope.veiculo.veiculo_vendido_data_obj != null){
                $scope.veiculo.veiculo_vendido_data = Math.floor($scope.veiculo.veiculo_vendido_data_obj.getTime() / 1000);
            }

            if($scope.veiculo.veiculo_sinistro_data_obj != null){
                $scope.veiculo.veiculo_sinistro_data = Math.floor($scope.veiculo.veiculo_sinistro_data_obj.getTime() / 1000);
            }
        
            $rootScope.is_loading = true;

            if($rootScope.$state.name == "insert-veiculo"){

                $http.post('/api/public/veiculos/insert', $scope.veiculo).then(function (response) {
                    
                    if(response.data.result){

                        $scope.allow_change_page = true;

                        ngToast.create({
                            className: 'success',
                            content: "Veiculo cadastrado com sucesso!"
                        });

                        $location.path('/veiculos');

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

                $http.post('/api/public/veiculos/update', $scope.veiculo ).then(function (response) {
                    
                    if(response.data.result){

                        $scope.allow_change_page = true;

                        ngToast.create({
                            className: 'success',
                            content: "Veículo editado com sucesso!"
                        });

                        $location.path('/veiculos');

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
            }).finally(function() {
                $rootScope.is_loading = false;
            });

        }

        function get_tipos(){

            $http.get('/api/public/veiculos/tipos/get?getall=1').then(function (response) {
                $scope.veiculos_tipos = response.data.results;
            }).finally(function() {
                $rootScope.is_loading = false;
            });

        }

        
    }

})();