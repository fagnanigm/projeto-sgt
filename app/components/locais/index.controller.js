(function () {
    'use strict';

    angular
        .module('app')
        .controller('Locais.IndexController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage,GlobalServices) {
        
        $scope.locais = {};

        $scope.currentPage = ($rootScope.$state.name == 'locais-paged' ? $rootScope.$stateParams.page : '1' );

        $scope.get_locais = function(){

            $rootScope.is_loading = true;

            var rest_address = '/api/public/locais/get';

            // Pagination
            rest_address = rest_address + '?current_page=' + $scope.currentPage;

            // Filter
            $.each($rootScope.get_filters, function(key, val){
                rest_address += '&' + key + '=' + val;
            });

            $http.get(rest_address).then(function (response) {

                $scope.locais = response.data;
                $scope.locais.config.current_page = parseInt($scope.locais.config.current_page);

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
        $scope.get_locais();
    
        $scope.delete_local = function(id){
            if(confirm("Deseja excluir esse local?")){
                $rootScope.is_loading = true;
                $http.post('/api/public/locais/delete',{ id : id }).then(function (response) {
                    $scope.get_locais();

                    ngToast.create({
                        className: 'success',
                        content: 'Local excluído com sucesso'
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