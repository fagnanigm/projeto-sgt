(function () {
    'use strict';

    angular
        .module('app')
        .controller('Cotacoes.VisualizarController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {
        
        
        function get_cotacao(){

            $http.get('/api/public/cotacoes/get/'+$rootScope.$stateParams.id_cotacao).then(function (response) {
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


        $scope.print_cotacao = function(id){

            $rootScope.is_loading = true;

            var param = {
                id_cotacao: id
            }

            $http.post('/api/public/impressoes/cotacao', param).then(function (response) {

                if(response.data.result){

                    window.open('/api/public' + response.data.file, '_blank');

                }else{
                    ngToast.create({
                        className: 'danger',
                        content: response.data.error
                    });
                }

            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.error;
            }).finally(function() {
                $rootScope.is_loading = false;
            });

        }


    }

})();