(function () {
    'use strict';

    angular
        .module('app')
        .controller('Produtos.GerenciaController', Controller);

    function Controller($rootScope,$scope,$http,ngToast,$location,GlobalServices, $localStorage) {
        var vm = this;

        $scope.produto = {};

        initController();

        function get_produto(){

            if($rootScope.$state.name == "insert-produto"){

                $scope.produto = {
                    id_author : $localStorage.currentUser.id,
                    id_empresa : $localStorage.currentEmpresaId
                }

            }else{

                $http.get('/api/public/produtos/get/' + $rootScope.$stateParams.id_produto + '?context=' + $localStorage.currentEmpresaId).then(function (response) {
                    $scope.produto = response.data.produto;
                    $scope.produto.context = $localStorage.currentEmpresaId;
                }, function(response) {
                    $rootScope.is_error = true;
                    $rootScope.is_error_text = "Erro: " + response.data.error;
                }).finally(function() {
                    $rootScope.is_loading = false;
                });

            }

        }

        function initController() {
        	get_produto();
        }


        vm.setProduto = function(){

            var error = 0;

            // VALIDAÇÃO
    
            if(error == 0){

                $rootScope.is_loading = true;

                if($rootScope.$state.name == "insert-produto"){

                    $http.post('/api/public/produtos/insert', $scope.produto).then(function (response) {
                        
                        if(response.data.result){

                            ngToast.create({
                                className: 'success',
                                content: "Produto cadastrado com sucesso!"
                            });

                            $location.path('/produtos');

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

                    console.log($scope.produto);

                    $http.post('/api/public/produtos/update', $scope.produto ).then(function (response) {
                        
                        if(response.data.result){

                            ngToast.create({
                                className: 'success',
                                content: "Produto editado com sucesso!"
                            });

                            $location.path('/produtos');

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