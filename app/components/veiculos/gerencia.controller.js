(function () {
    'use strict';

    angular
        .module('app')
        .controller('Veiculos.GerenciaController', Controller);

    function Controller($rootScope,$scope,$http,ngToast,$location,GlobalServices, $localStorage) {
        var vm = this;

        $scope.veiculo = {};

        $scope.beneficios = [
            "IPVA",
            "IPVA isento",
            "Seguro de obrigatório",
            "Licenciado",
            "Permanente",
            "Credenciado PRF",
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
                    id_empresa : $localStorage.currentEmpresaId, 
                    veiculo_person : 'proprio',
                    veiculo_beneficios_checked : []
                }

            }else{

                $http.get('/api/public/locais/get/' + $rootScope.$stateParams.id_local + '?context=' + $localStorage.currentEmpresaId).then(function (response) {
                    $scope.veiculo = response.data.veiculo;
                    $scope.veiculo.context = $localStorage.currentEmpresaId;
                }, function(response) {
                    $rootScope.is_error = true;
                    $rootScope.is_error_text = "Erro: " + response.data.error;
                }).finally(function() {
                    $rootScope.is_loading = false;
                });

            }

        }

        function initController() {
        	get_veiculo();
        }


        vm.setVeiculo = function(){

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


            var error = 0;
            
            // Validação 
    
            if(error == 0){

                $rootScope.is_loading = true;

                if($rootScope.$state.name == "insert-veiculo"){

                    $http.post('/api/public/veiculos/insert', $scope.veiculo).then(function (response) {
                        
                        if(response.data.result){

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

                    $http.post('/api/public/locais/update', $scope.veiculo ).then(function (response) {
                        
                        if(response.data.result){

                            ngToast.create({
                                className: 'success',
                                content: "veiculo editado com sucesso!"
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



        
    }

})();