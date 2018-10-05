(function () {
    'use strict';

    angular
        .module('app')
        .controller('Categorias.GerenciaController', Controller);

    function Controller($rootScope,$scope,$http,ngToast,$location,GlobalServices, $localStorage) {
        
        var vm = this;

        $scope.categoria = {};

        initController();

        function get_categoria(){

            if($rootScope.$state.name == "insert-categoria"){

                $scope.categoria = {
                    id_author : $localStorage.currentUser.id
                }

                $rootScope.is_loading = false;

            }else{

                $http.get('/api/public/categorias/get/' + $rootScope.$stateParams.id_categoria ).then(function (response) {
                    $scope.categoria = response.data.categoria;
                }, function(response) {
                    $rootScope.is_error = true;
                    $rootScope.is_error_text = "Erro: " + response.data.error;
                }).finally(function() {
                    $rootScope.is_loading = false;
                });

            }

        }

        function initController() {
        	get_categoria();
        }


        vm.setCategoria = function(){

            var error = 0;

            // VALIDAÇÃO
    
            if(error == 0){

                $rootScope.is_loading = true;

                if($rootScope.$state.name == "insert-categoria"){

                    $http.post('/api/public/categorias/insert', $scope.categoria).then(function (response) {
                        
                        if(response.data.result){

                            ngToast.create({
                                className: 'success',
                                content: "Categoria cadastrada com sucesso!"
                            });

                            $location.path('/categorias');

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

                    $http.post('/api/public/categorias/update', $scope.categoria ).then(function (response) {
                        
                        if(response.data.result){

                            ngToast.create({
                                className: 'success',
                                content: "categoria editada com sucesso!"
                            });

                            $location.path('/categorias');

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