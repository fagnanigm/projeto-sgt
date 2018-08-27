(function () {
    'use strict';

    angular
        .module('app')
        .controller('Cotacoes.VisualizarController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {
        
        
        function get_cotacao(){

            $http.get('/api/public/cotacoes/get/'+$rootScope.$stateParams.id_cotacao+'?context='+$localStorage.currentEmpresaId).then(function (response) {
                $scope.cotacao = response.data.cotacao;
                console.log(  $scope.cotacao)

            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.error;
            }).finally(function() {
                $rootScope.is_loading = false;
            });

        }

        get_cotacao();

    }

})();