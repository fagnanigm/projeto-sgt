(function () {
    'use strict';

    angular
        .module('app')
        .controller('Motoristas.GerenciaController', Controller);

    function Controller($rootScope,$scope,$http,ngToast,$location,GlobalServices, $localStorage) {
        var vm = this;

        $scope.motorista = {};

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

        function get_motorista(){

            if($rootScope.$state.name == "insert-motorista"){

                $scope.motorista = {
                    id_author : $localStorage.currentUser.id,
                    id_empresa : $localStorage.currentEmpresaId, 
                    veiculo_person : 'proprio',
                    veiculo_beneficios_checked : []
                }

            }else{

                $http.get('/api/public/locais/get/' + $rootScope.$stateParams.id_local + '?context=' + $localStorage.currentEmpresaId).then(function (response) {
                    $scope.motorista = response.data.motorista;
                    $scope.motorista.context = $localStorage.currentEmpresaId;
                }, function(response) {
                    $rootScope.is_error = true;
                    $rootScope.is_error_text = "Erro: " + response.data.error;
                }).finally(function() {
                    $rootScope.is_loading = false;
                });

            }

        }

        function initController() {
        	get_motorista();
        }


        vm.setMotorista = function(){

            // Tratamento 
            $scope.motorista.veiculo_beneficios = [];

            $.each($scope.motorista.veiculo_beneficios_checked, function(key, val){

                if(val){
                    $scope.motorista.veiculo_beneficios.push({
                        key : key,
                        beneficio : $scope.beneficios[key]
                    })
                }

            });

            $scope.motorista.veiculo_beneficios = JSON.stringify($scope.motorista.veiculo_beneficios);


            var error = 0;
            
            // Validação 
    
            if(error == 0){

                $rootScope.is_loading = true;

                if($rootScope.$state.name == "insert-motorista"){

                    $http.post('/api/public/veiculos/insert', $scope.motorista).then(function (response) {
                        
                        if(response.data.result){

                            ngToast.create({
                                className: 'success',
                                content: "motorista cadastrado com sucesso!"
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

                    $http.post('/api/public/locais/update', $scope.motorista ).then(function (response) {
                        
                        if(response.data.result){

                            ngToast.create({
                                className: 'success',
                                content: "motorista editado com sucesso!"
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