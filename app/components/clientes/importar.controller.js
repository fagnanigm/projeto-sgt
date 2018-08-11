(function () {
    'use strict';

    angular
        .module('app')
        .controller('Clientes.ImportController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {

        $scope.import = false;
        
        $scope.importar = function(){

            if(confirm("O precesso levará aproximadamente de 10 a 20 minutos, deseja continuar?")){

                $rootScope.is_loading = true;

                $http.post('/api/public/clientes/importOmie', { context : $localStorage.currentEmpresaId }).then(function (response) {

                    $scope.import = response.data;
                    console.log($scope.import);

                }, function(response) {
                    $rootScope.is_error = true;
                    $rootScope.is_error_text = "Erro: " + response.data.error;
                }).finally(function() {
                    $rootScope.is_loading = false;
                });

                

            }

        }

    }

})();