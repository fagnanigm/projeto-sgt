(function () {
    'use strict';

    angular
        .module('app')
        .controller('Vendedores.IndexController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {
        
        $scope.vendedores = {};

        $scope.currentPage = ($rootScope.$state.name == 'vendedores-paged' ? $rootScope.$stateParams.page : '1' );

        $scope.get_vendedores = function(){

            $rootScope.is_loading = true;

            var rest_address = '/api/public/vendedores/get';

            // Pagination
            rest_address = rest_address + '?current_page=' + $scope.currentPage;

            // Filter
            $.each($rootScope.get_filters, function(key, val){
                rest_address += '&' + key + '=' + val;
            });

        	$http.get(rest_address).then(function (response) {

                $scope.vendedores = response.data;
                $scope.vendedores.config.current_page = parseInt($scope.vendedores.config.current_page);

			}, function(response) {
				$rootScope.is_error = true;
				$rootScope.is_error_text = "Erro: " + response.data.error;
			}).finally(function() {
				$rootScope.is_loading = false;
			});
        }


        $rootScope.is_loading = true;
        $scope.get_vendedores();

    
        $scope.delete_vendedor = function(id){
            if(confirm("Deseja excluir esse vendedor?")){
                $rootScope.is_loading = true;
                $http.post('/api/public/vendedores/delete',{ id : id }).then(function (response) {
                    $scope.get_vendedores();

                    ngToast.create({
                        className: 'success',
                        content: 'Vendedor excluído com sucesso'
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