(function () {
    'use strict';

    angular
        .module('app')
        .controller('Cotacoes.VisualizarController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {
        
        
        function get_cotacao(){

            $http.get('/api/public/cotacoes/get/'+$rootScope.$stateParams.id_cotacao+'?context='+$localStorage.currentEmpresaId).then(function (response) {
                $scope.cotacao = response.data.cotacao;

                calc_total_cotacao_objeto();

            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.error;
            }).finally(function() {
                $rootScope.is_loading = false;
            });

        }

        get_cotacao();


        function calc_total_cotacao_objeto(){

            $scope.total_cotacao_objetos = 0;
            $.each($scope.cotacao.cotacao_caracteristica_objetos, function(key, val){
                $scope.total_cotacao_objetos += parseFloat(val.objeto_valor_total);
            });

        }

    }

})();