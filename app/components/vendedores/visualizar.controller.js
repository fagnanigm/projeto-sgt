(function () {
    'use strict';

    angular
        .module('app')
        .controller('Vendedores.VisualizarController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {
        
        
        function get_vendedor(){

            $http.get('/api/public/vendedores/get/'+$rootScope.$stateParams.id_vendedor).then(function (response) {
                $scope.vendedor = response.data.vendedor;
            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.error;
            }).finally(function() {
                $rootScope.is_loading = false;
            });

        }

        get_vendedor();

    }

})();