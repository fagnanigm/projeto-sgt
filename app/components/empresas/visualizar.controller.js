(function () {
    'use strict';

    angular
        .module('app')
        .controller('Empresas.VisualizarController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {
        
        
        function get_empresa(){

            $http.get('/api/public/empresas/get/'+$rootScope.$stateParams.id_empresa).then(function (response) {
                $scope.empresa = response.data.empresa;
            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.error;
            }).finally(function() {
                $rootScope.is_loading = false;
            });

        }

        get_empresa();

    }

})();