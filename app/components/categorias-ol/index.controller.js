(function () {
    'use strict';

    angular
        .module('app')
        .controller('Categorias.IndexController', Controller);

    function Controller($rootScope,$scope,$http,ngToast,GlobalServices) {
        var vm = this;

        $scope.categoria_data = {
            id_parent : '0'
        };

        $scope.categorias_mothers = [];

        function getCategories(){

            $http.post('http://model.exodocientifica.com.br/categorias/read').then(function (response) {

                $scope.categorias = response.data.data;
                $scope.categorias_hierarchical = GlobalServices.hierarchical_decode(response.data.hierarchical,0);

            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.message;
            }).finally(function() {
                $rootScope.is_loading = false;
            });

        }

        initController();

        function initController() {
            $rootScope.is_loading = true;
            getCategories();

            $scope.$watch('categorias', function() {
                $scope.categorias_mothers = [];
                $.each($scope.categorias,function(key,val){
                    //if(val.id_parent == '0'){
                        $scope.categorias_mothers.push($scope.categorias[key]);
                    //}
                });
            });


            

        }

            
        $scope.insertCategoria = function(){

            $rootScope.is_loading = true;

            $http.post('http://model.exodocientifica.com.br/categorias/insert',{ values : $scope.categoria_data }).then(function (response) {

                if(response.data.result){

                    ngToast.create({
                        className: 'success',
                        content: "Categoria cadastrada com sucesso"
                    });

                    getCategories();

                    $scope.categoria_data = {
                        id_parent : '0',
                        description : ''
                    };

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

        $scope.removeCategoria = function(id){

            if(confirm("Deseja apagar esta categoria?")){

                $rootScope.is_loading = true;

                $http.post('http://model.exodocientifica.com.br/categorias/remove',{ id : id }).then(function (response) {
                
                    if(response.data.result){

                        ngToast.create({
                            className: 'success',
                            content: "Categoria removida com sucesso"
                        });

                        getCategories();

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