(function () {
    'use strict';

    angular
        .module('app')
        .controller('Empresas.IndexController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage,GlobalServices) {
        
        $scope.empresas = {};

        $scope.currentPage = ($rootScope.$state.name == 'empresas-paged' ? $rootScope.$stateParams.page : '1' );

        $scope.get_empresas = function(){

            $rootScope.is_loading = true;

            var rest_address = '/api/public/empresas/get';

            // Pagination
            rest_address = rest_address + '?current_page=' + $scope.currentPage;

            // Filter
            $.each($rootScope.get_filters, function(key, val){
                rest_address += '&' + key + '=' + val;
            });

        	$http.get(rest_address).then(function (response) {

                $scope.empresas = response.data;
                $scope.empresas.config.current_page = parseInt($scope.empresas.config.current_page);

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
        $scope.get_empresas();

    
        $scope.delete_empresa = function(id){
            if(confirm("Deseja excluir essa empresa?")){

                if(id == $localStorage.currentEmpresaId){
                    delete $localStorage.currentEmpresaId;
                }

                $rootScope.is_loading = true;
                $http.post('/api/public/empresas/delete',{ id : id }).then(function (response) {
                    $scope.get_empresas();

                    ngToast.create({
                        className: 'success',
                        content: 'Empresa excluída com sucesso'
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