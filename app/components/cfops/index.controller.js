(function () {
    'use strict';

    angular
        .module('app')
        .controller('Cfops.IndexController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage,GlobalServices) {
        
        $scope.results = {};

        $scope.currentPage = ($rootScope.$state.name == 'cfops-paged' ? $rootScope.$stateParams.page : '1' );

        $scope.get_results = function(){

            $rootScope.is_loading = true;

            var rest_address = '/api/public/cfop/get';

            // Pagination
            rest_address = rest_address + '?current_page=' + $scope.currentPage;

            // Filter
            $.each($rootScope.get_filters, function(key, val){
                rest_address += '&' + key + '=' + val;
            });

            $http.get(rest_address).then(function (response) {

                // Configurações
                $scope.config = response.data.config;
                $scope.config.current_page = parseInt($scope.config.current_page);

                // Resultados
                $scope.results = response.data.results;

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
        $scope.get_results();
    
        $scope.delete_item = function(id){
            if(confirm("Deseja excluir esse CFOP?")){
                $rootScope.is_loading = true;
                $http.post('/api/public/cfop/delete',{ id : id }).then(function (response) {
                    $scope.get_results();

                    ngToast.create({
                        className: 'success',
                        content: 'CFOP excluído com sucesso'
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