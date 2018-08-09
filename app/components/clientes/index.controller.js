(function () {
    'use strict';

    angular
        .module('app')
        .controller('Clientes.IndexController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {
        
        $scope.users = {};

        $scope.currentPage = ($rootScope.$state.name == 'clientes-paged' ? $rootScope.$stateParams.page : '1' );

        $scope.get_clientes = function(){

            $rootScope.is_loading = true;

        	$http.get('/api/public/clientes/get?context='+$localStorage.currentEmpresaId+'&current_page='+$scope.currentPage).then(function (response) {

                $scope.clientes = response.data;
                $scope.clientes.config.current_page = parseInt($scope.clientes.config.current_page);

                console.log( $scope.clientes);

			}, function(response) {
				$rootScope.is_error = true;
				$rootScope.is_error_text = "Erro: " + response.data.error;
			}).finally(function() {
				$rootScope.is_loading = false;
			});
        }


        $rootScope.is_loading = true;
        $scope.get_clientes();

    
        $scope.delete_cliente = function(id){
            if(confirm("Deseja excluir esse cliente?")){
                $rootScope.is_loading = true;
                $http.post('/api/public/clientes/delete',{ id : id }).then(function (response) {
                    $scope.get_clientes();

                    ngToast.create({
                        className: 'success',
                        content: 'Cliente excluído com sucesso'
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