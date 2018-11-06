(function () {
    'use strict';

    angular
        .module('app')
        .controller('Veiculos.IndexController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage,GlobalServices) {
        
        $scope.veiculos = {};

        $scope.currentPage = ($rootScope.$state.name == 'veiculos-paged' ? $rootScope.$stateParams.page : '1' );

        $scope.get_veiculos = function(){

            $rootScope.is_loading = true;

            var rest_address = '/api/public/veiculos/get';

            // Pagination
            rest_address = rest_address + '?current_page=' + $scope.currentPage;

            // Filter
            $.each($rootScope.get_filters, function(key, val){
                rest_address += '&' + key + '=' + val;
            });

            $http.get(rest_address).then(function (response) {

                $scope.veiculos = response.data;
                $scope.veiculos.config.current_page = parseInt($scope.veiculos.config.current_page);

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
        $scope.get_veiculos();

    
        $scope.delete_veiculo = function(id){
            if(confirm("Deseja excluir esse veiculo?")){
                $rootScope.is_loading = true;
                $http.post('/api/public/veiculos/delete',{ id : id }).then(function (response) {
                    $scope.get_veiculos();

                    ngToast.create({
                        className: 'success',
                        content: 'Veículo excluído com sucesso'
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