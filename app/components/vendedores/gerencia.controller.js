(function () {
    'use strict';

    angular
        .module('app')
        .controller('Vendedores.GerenciaController', Controller);

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

        $scope.vendedor = {};

        initController();

        function get_vendedor(){

            if($rootScope.$state.name == "insert-vendedor"){

                $scope.vendedor = {
                    id_author : $localStorage.currentUser.id,
                }

                $rootScope.is_loading = false;

            }else{

                $http.get('/api/public/vendedores/get/' + $rootScope.$stateParams.id_vendedor).then(function (response) {
                    
                    $scope.vendedor = response.data.vendedor;

                }, function(response) {
                    $rootScope.is_error = true;
                    $rootScope.is_error_text = "Erro: " + response.data.error;
                }).finally(function() {
                    $rootScope.is_loading = false;
                });

            }

        }        

        function initController() {
        	get_vendedor();
        }        

        vm.setVendedor = function(){

            $rootScope.is_loading = true;

            if($rootScope.$state.name == "insert-vendedor"){

                $http.post('/api/public/vendedores/insert', $scope.vendedor).then(function (response) {
                    
                    if(response.data.result){

                        $scope.allow_change_page = true;

                        ngToast.create({
                            className: 'success',
                            content: "Vendedor cadastrado com sucesso!"
                        });

                        $location.path('/vendedores');

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

                $http.post('/api/public/vendedores/update', $scope.vendedor ).then(function (response) {

                    if(response.data.result){

                        $scope.allow_change_page = true;

                        ngToast.create({
                            className: 'success',
                            content: "Vendedor editado com sucesso!"
                        });

                        $location.path('/vendedores');

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

})();