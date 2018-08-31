(function () {
    'use strict';

    angular
        .module('app')
        .controller('Categorias.VisualizarController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {
        
        function get_categoria(){

            $http.get('/api/public/categorias/get/'+$rootScope.$stateParams.id_categoria).then(function (response) {
                $scope.categoria = response.data.categoria;
            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.error;
            }).finally(function() {
                $rootScope.is_loading = false;
            });

        }

        get_categoria();

    }

})();