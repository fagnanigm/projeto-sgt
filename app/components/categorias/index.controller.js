(function () {
    'use strict';

    angular
        .module('app')
        .controller('Categorias.IndexController', Controller);

    function Controller($rootScope,$scope,$http,ngToast,$location,$localStorage,GlobalServices) {

        var vm = this;

        $scope.categorias = {};

        $scope.currentPage = ($rootScope.$state.name == 'categorias-paged' ? $rootScope.$stateParams.page : '1' );

        $scope.get_categorias = function(){

            $rootScope.is_loading = true;

            var rest_address = '/api/public/categorias/get';

            // Pagination
            rest_address = rest_address + '?current_page=' + $scope.currentPage;

            // Filter
            $.each($rootScope.get_filters, function(key, val){
                rest_address += '&' + key + '=' + val;
            });

            $http.get(rest_address).then(function (response) {

                $scope.categorias = response.data;
                $scope.categorias.config.current_page = parseInt($scope.categorias.config.current_page);

                // Configurações
                $scope.config = response.data.config;
                $scope.config.current_page = parseInt($scope.config.current_page);

                // Paginate
                $scope.paginate = GlobalServices.get_paginate_list(response.data.config);

            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.error;
            }).finally(function() {
                $rootScope.is_loading = false;
            });
        }

        $rootScope.is_loading = true;
        $scope.get_categorias();
    
        $scope.delete_categoria = function(id){
            if(confirm("Deseja excluir essa categoria?")){
                $rootScope.is_loading = true;
                $http.post('/api/public/categorias/delete',{ id : id }).then(function (response) {
                    $scope.get_categorias();

                    ngToast.create({
                        className: 'success',
                        content: 'Categoria excluída com sucesso'
                    });

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