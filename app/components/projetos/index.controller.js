(function () {
    'use strict';

    angular
        .module('app')
        .controller('Projetos.IndexController', Controller);

        function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage,GlobalServices) {
        
        $scope.projetos = {};

        $scope.currentPage = ($rootScope.$state.name == 'projetos-paged' ? $rootScope.$stateParams.page : '1' );

        $scope.get_projetos = function(){

            $rootScope.is_loading = true;

            var rest_address = '/api/public/projetos/get';

            // Pagination
            rest_address = rest_address + '?current_page=' + $scope.currentPage;

            // Filter
            $.each($rootScope.get_filters, function(key, val){
                rest_address += '&' + key + '=' + val;
            });

            $http.get(rest_address).then(function (response) {

                $scope.projetos = response.data;
                $scope.projetos.config.current_page = parseInt($scope.projetos.config.current_page);

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
        $scope.get_projetos();
    

        $scope.print_projeto_custo = function(id_projeto){

            $rootScope.is_loading = true;

            $http.post('/api/public/relatorios/projeto/custo', { id_projeto: id_projeto }).then(function (response) {

                console.log(response);

                $rootScope.is_loading = false;

                if(response.data.result){
                    
                    window.open('/api/public' + response.data.file, '_blank');

                }else{
                    ngToast.create({
                        className: 'danger',
                        content: response.data.error
                    });
                }

            });

        }

    }

})();