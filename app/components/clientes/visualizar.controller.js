(function () {
    'use strict';

    angular
        .module('app')
        .controller('Clientes.VisualizarController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {
        
        
        function get_cliente(){

                $http.get('/api/public/clientes/get/'+$rootScope.$stateParams.id_cliente).then(function (response) {
                    $scope.cliente = response.data.cliente;
                    
                    console.log(  $scope.cliente)

                }, function(response) {
                    $rootScope.is_error = true;
                    $rootScope.is_error_text = "Erro: " + response.data.error;
                }).finally(function() {
                    $rootScope.is_loading = false;
                });

        

        }

        get_cliente();

    }

})();