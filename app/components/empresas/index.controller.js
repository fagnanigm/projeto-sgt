(function () {
    'use strict';

    angular
        .module('app')
        .controller('Empresas.IndexController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {
        
        $scope.empresas = {};

        $scope.currentPage = ($rootScope.$state.name == 'empresas-paged' ? $rootScope.$stateParams.page : '1' );

        $scope.get_empresas = function(){

            $rootScope.is_loading = true;

        	$http.get('/api/public/empresas/get?current_page='+$scope.currentPage).then(function (response) {

                $scope.empresas = response.data;
                $scope.empresas.config.current_page = parseInt($scope.empresas.config.current_page);

                console.log($scope.empresas);

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