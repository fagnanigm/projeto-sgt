(function () {
    'use strict';

    angular
        .module('app')
        .controller('Usuarios.GerenciaController', Controller);

    function Controller($rootScope,$scope,$http,ngToast,$location,GlobalServices) {
        var vm = this;

        $scope.user = {};

        initController();

        function get_user(){

            if($rootScope.$state.name == "insert-usuario"){

                $scope.user = {
                    person : 'f',
                    permission : 'master'
                }

            }else{

                $http.get('/api/public/users/get/' + $rootScope.$stateParams.id_user).then(function (response) {
                    $scope.user = response.data.user;

                    $scope.user.phone1 = GlobalServices.phone_parser({ ddd : $scope.user.ddd_phone_01, number : $scope.user.phone_01 }, 'implode');
                    $scope.user.phone2 = GlobalServices.phone_parser({ ddd : $scope.user.ddd_phone_02, number : $scope.user.phone_02 }, 'implode');

                }, function(response) {
                    $rootScope.is_error = true;
                    $rootScope.is_error_text = "Erro: " + response.data.error;
                }).finally(function() {
                    $rootScope.is_loading = false;
                });
                

            }

        }

        function initController() {
        	get_user();
        }


        vm.setUser = function(){

            var error = 0;
            
            // Validação 
            if(($rootScope.$state.name == "insert-usuario")){
                if($scope.user.password != $scope.user.password2){
                    ngToast.create({
                        className: 'danger',
                        content: 'As senhas não se conhecidem.'
                    });
                    error++;
                }else{
                    if($scope.user.password.length < 6){
                        ngToast.create({
                            className: 'danger',
                            content: 'A senha deve ter 6 ou mais caracteres.'
                        });
                        error++;
                    }
                }
            }

            // Conversão de telefone para servidor

            var phone1 = GlobalServices.phone_parser({ number : $scope.user.phone1 }, 'explode');
            $scope.user.ddd_phone_01 = phone1.ddd;
            $scope.user.phone_01 = phone1.number;

            var phone2 = GlobalServices.phone_parser({ number : $scope.user.phone2 }, 'explode');
            $scope.user.ddd_phone_02 = phone2.ddd;
            $scope.user.phone_02 = phone2.number;

            
            if(error == 0){

                $rootScope.is_loading = true;

                if($rootScope.$state.name == "insert-usuario"){

                    $http.post('/api/public/users/insert', $scope.user).then(function (response) {
                        
                        if(response.data.result){

                            ngToast.create({
                                className: 'success',
                                content: "Usuário cadastrado com sucesso!"
                            });

                            $location.path('/usuarios');

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

                    delete $scope.user.phone1;
                    delete $scope.user.phone2;

                    $http.post('/api/public/users/update', $scope.user ).then(function (response) {
                        
                        if(response.data.result){

                            ngToast.create({
                                className: 'success',
                                content: "Usuário editado com sucesso!"
                            });

                            $location.path('/usuarios');

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