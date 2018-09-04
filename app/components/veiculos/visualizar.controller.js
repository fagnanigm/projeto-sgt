(function () {
    'use strict';

    angular
        .module('app')
        .controller('Veiculos.VisualizarController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {
        
        
        function get_veiculo(){

            $http.get('/api/public/veiculos/get/'+$rootScope.$stateParams.id_veiculo).then(function (response) {
                $scope.veiculo = response.data.veiculo;
                $scope.veiculo.veiculo_tipo = get_veiculo_tipo_text($scope.veiculo.veiculo_tipo); 
                $scope.veiculo.veiculo_status = get_veiculo_status_text($scope.veiculo.veiculo_status); 
                $scope.veiculo.veiculo_beneficios = $.parseJSON($scope.veiculo.veiculo_beneficios);
            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.error;
            }).finally(function() {
                $rootScope.is_loading = false;
            });

        }

        function get_veiculo_tipo_text(term){

            switch(term){
                case 'l':
                    return 'L';
                    break;
                case 'ls':
                    return 'LS';
                    break;
                case 'lt': 
                    return 'LT';
                    break;
                case 'lt4':
                    return 'LT4';
                    break;
                case 'c-2-eixos':
                    return 'C 2 Eixos'
                    break;
                case 'c-3-eixos':
                    return 'C 3 Eixos';
                    break;
                case 'c-4-eixos':
                    return 'C 4 Eixos';
                    break;
                default:
                    return term;
            }   
                    
        }

        function get_veiculo_status_text(term){

            switch(term){
                case 'em-viagem':
                    return 'Em Viagem';
                    break;
                case 'patio':
                    return 'Pátio';
                    break;
                case 'em-manutencao': 
                    return 'Em Manutenção';
                    break;
                case 'emprestado':
                    return 'Emprestado';
                    break;
                case 'desativado':
                    return 'Desativado'
                    break;
                case 'vendido':
                    return 'Vendido';
                    break;
                case 'sinistro':
                    return 'Sinistro';
                    break;
                default:
                    return term;
            }   

        }

        get_veiculo();

    }

})();