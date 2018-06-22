(function () {
    'use strict';

    angular
        .module('app')
        .controller('Usuarios.GerenciaController', Controller);

    function Controller($rootScope,$scope,$http,ngToast,$location) {
        var vm = this;

        $scope.user = {};

        initController();

        function get_user(){


            if($rootScope.$state.name == "insert-usuario"){

                $scope.user = {
                    person_type : 'fisica',
                    type : 'admin'
                }

            }else{

                var data = {
                    conditions : { id : $rootScope.$stateParams.id_user },
                    operator : { 0 : '=' },
                    single : true
                }

                $http.post('http://model.exodocientifica.com.br/usuarios/read',data).then(function (response) {
                    $scope.user = response.data;
                }, function(response) {
                    $rootScope.is_error = true;
                    $rootScope.is_error_text = "Erro: " + response.data.message;
                }).finally(function() {
                    $rootScope.is_loading = false;
                });
                

            }

        }

        function initController() {
        	//$rootScope.is_loading = true;
        	get_user();
        }


        vm.setUser = function(){

            /*

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

            if(error == 0){

                $rootScope.is_loading = true;

                if($rootScope.$state.name == "insert-usuario"){

                    $http.post('http://model.exodocientifica.com.br/usuarios/insert', $scope.user).then(function (response) {
                        
                        if(response.data.result){

                            ngToast.create({
                                className: 'success',
                                content: "Usuário cadastrado com sucesso!"
                            });

                            setTimeout(function(){ $location.path('/usuarios'); },600);

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

                    var data = {
                        values : $scope.user,
                        condition : {
                            id : $rootScope.$stateParams.id_user
                        }
                    }

                    $http.post('http://model.exodocientifica.com.br/usuarios/update', data).then(function (response) {

                        
                        if(response.data.result){

                            ngToast.create({
                                className: 'success',
                                content: "Usuário editado com sucesso!"
                            });

                            setTimeout(function(){ $location.path('/usuarios'); },1000);

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

            */

        }

        
    }

})();