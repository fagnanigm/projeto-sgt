(function () {
    'use strict';

    angular
        .module('app')
        .controller('Empresas.GerenciaController', Controller);

    function Controller($rootScope,$scope,$http,ngToast,$location,GlobalServices, $localStorage) {
        var vm = this;

        $scope.empresa = {};

        initController();

        function get_empresa(){

            if($rootScope.$state.name == "insert-empresa"){

                $scope.empresa = {
                    id_author : $localStorage.currentUser.id,
                    empresa_estado : '0'
                }

            }else{

                $http.get('/api/public/empresas/get/' + $rootScope.$stateParams.id_empresa).then(function (response) {
                    $scope.empresa = response.data.empresa;
                    $scope.empresa.phone1 = GlobalServices.phone_parser({ ddd : $scope.empresa.empresa_phone_ddd, number : $scope.empresa.empresa_phone }, 'implode');
                }, function(response) {
                    $rootScope.is_error = true;
                    $rootScope.is_error_text = "Erro: " + response.data.error;
                }).finally(function() {
                    $rootScope.is_loading = false;
                });

            }

        }

        function initController() {
        	get_empresa();
        }


        vm.setEmpresa = function(){


            var error = 0;
            
            // Validação 
            

            // Conversão de telefone para servidor
            var phone1 = GlobalServices.phone_parser({ number : $scope.empresa.phone1 }, 'explode');
            $scope.empresa.empresa_phone_ddd = phone1.ddd;
            $scope.empresa.empresa_phone = phone1.number;
            
            if(error == 0){

                $rootScope.is_loading = true;

                if($rootScope.$state.name == "insert-empresa"){

                    $http.post('/api/public/empresas/insert', $scope.empresa).then(function (response) {
                        
                        if(response.data.result){

                            ngToast.create({
                                className: 'success',
                                content: "Empresa cadastrada com sucesso!"
                            });

                            $location.path('/empresas');

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

                    delete $scope.empresa.phone1;

                    $http.post('/api/public/empresas/update', $scope.empresa ).then(function (response) {
                        
                        if(response.data.result){

                            ngToast.create({
                                className: 'success',
                                content: "Empresa editada com sucesso!"
                            });

                            $location.path('/empresas');

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