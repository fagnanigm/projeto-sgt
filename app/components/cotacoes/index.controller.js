(function () {
    'use strict';

    angular
        .module('app')
        .controller('Cotacoes.IndexController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {
        
        $scope.cotacoes = {};

        $scope.currentPage = ($rootScope.$state.name == 'cotacoes-paged' ? $rootScope.$stateParams.page : '1' );

        $scope.get_cotacoes = function(){

            $rootScope.is_loading = true;

        	$http.get('/api/public/cotacoes/get?context='+$localStorage.currentEmpresaId+'&current_page='+$scope.currentPage).then(function (response) {

                $scope.cotacoes = response.data;
                $scope.cotacoes.config.current_page = parseInt($scope.cotacoes.config.current_page);

			}, function(response) {
				$rootScope.is_error = true;
				$rootScope.is_error_text = "Erro: " + response.data.error;
			}).finally(function() {
				$rootScope.is_loading = false;
			});
        }


        $rootScope.is_loading = true;
        $scope.get_cotacoes();

    
        $scope.delete_cotacao = function(id){
            if(confirm("Deseja excluir essa cotação?")){
                $rootScope.is_loading = true;
                $http.post('/api/public/cotacoes/delete',{ id : id }).then(function (response) {
                    $scope.get_cotacoes();

                    ngToast.create({
                        className: 'success',
                        content: 'Cotação excluída com sucesso'
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