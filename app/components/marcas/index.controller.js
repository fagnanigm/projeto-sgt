(function () {
    'use strict';

    angular
        .module('app')
        .controller('Marcas.IndexController', Controller);

    function Controller($rootScope,$scope,$http,ngToast) {

        var vm = this;

        $scope.marcas = [];
        $scope.marca_data = {};

        initController();

        function getMarcas(){

            $http.post('http://model.exodocientifica.com.br/marcas/read').then(function (response) {
                $scope.marcas = response.data.data;
            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.message;
            }).finally(function() {
                $rootScope.is_loading = false;
            });

        }

        function initController() {
            $rootScope.is_loading = true;
            getMarcas();

        }

        $scope.insertMarca = function(){
            $rootScope.is_loading = true;
            $http.post('http://model.exodocientifica.com.br/marcas/insert', { values : $scope.marca_data }).then(function (response) {
                if(response.data.result){
                    $scope.marca_data = {};
                    getMarcas();
                    ngToast.create({
                        className: 'success',
                        content: "Marca cadastrada com sucesso"
                    });
                }              
            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.message;
            }).finally(function() {
                $rootScope.is_loading = false;
            });
        }


        $scope.removeMarca = function(id){

            if(confirm("Deseja apagar esta marca?")){

                $rootScope.is_loading = true;

                $http.post('http://model.exodocientifica.com.br/marcas/remove',{ id : id }).then(function (response) {
                
                    if(response.data.result){

                        ngToast.create({
                            className: 'success',
                            content: "Marca removida com sucesso"
                        });

                        getMarcas();

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