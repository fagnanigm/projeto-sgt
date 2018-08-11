(function () {
    'use strict';

    angular
        .module('app')
        .controller('Locais.GerenciaController', Controller);

    function Controller($rootScope,$scope,$http,ngToast,$location,GlobalServices, $localStorage) {
        var vm = this;

        $scope.local = {};

        initController();

        function get_local(){

            if($rootScope.$state.name == "insert-local"){

                $scope.local = {
                    id_author : $localStorage.currentUser.id,
                    id_empresa : $localStorage.currentEmpresaId, 
                    local_estado : '0',
                    local_pais : 'Brasil'
                }

            }else{

                $http.get('/api/public/locais/get/' + $rootScope.$stateParams.id_local + '?context=' + $localStorage.currentEmpresaId).then(function (response) {
                    $scope.local = response.data.local;
                    $scope.local.context = $localStorage.currentEmpresaId;
                }, function(response) {
                    $rootScope.is_error = true;
                    $rootScope.is_error_text = "Erro: " + response.data.error;
                }).finally(function() {
                    $rootScope.is_loading = false;
                });

            }

        }

        function initController() {
        	get_local();
        }


        vm.setLocal = function(){

            var error = 0;
            
            // Validação 
            if($scope.local.local_estado == '0'){
                ngToast.create({
                    className: 'danger',
                    content: "Estado inválido"
                });
                error++;
                return;
            }

            if($scope.local.local_pais == '0'){
                ngToast.create({
                    className: 'danger',
                    content: "País inválido"
                });
                error++;
                return;
            }

    
            if(error == 0){

                $rootScope.is_loading = true;

                if($rootScope.$state.name == "insert-local"){

                    $http.post('/api/public/locais/insert', $scope.local).then(function (response) {
                        
                        if(response.data.result){

                            ngToast.create({
                                className: 'success',
                                content: "Local cadastrado com sucesso!"
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

                }else{

                    $http.post('/api/public/locais/update', $scope.local ).then(function (response) {
                        
                        if(response.data.result){

                            ngToast.create({
                                className: 'success',
                                content: "Local editado com sucesso!"
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