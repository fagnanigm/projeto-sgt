(function () {
    'use strict';

    angular
        .module('app')
        .controller('Empresas.SelectController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {
        
        $scope.empresas = {};

        $scope.get_empresas = function(){

            $rootScope.is_loading = true;

        	$http.get('/api/public/empresas/get?getall=1').then(function (response) {

                $scope.empresas = response.data;
                
			}, function(response) {
				$rootScope.is_error = true;
				$rootScope.is_error_text = "Erro: " + response.data.error;
			}).finally(function() {
				$rootScope.is_loading = false;
			});
        }

        $rootScope.is_loading = true;
        $scope.get_empresas();

        $scope.setEmpresaContext = function(key){
            $localStorage.currentEmpresaId = $scope.empresas.results[key].id;
            $rootScope.selectedEmpresa = $scope.empresas.results[key];
            $location.path('/dashboard');
        }

    }

})();