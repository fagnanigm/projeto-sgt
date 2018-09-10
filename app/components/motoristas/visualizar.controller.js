(function () {
    'use strict';

    angular
        .module('app')
        .controller('Motoristas.VisualizarController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {
        
        
        function get_motorista(){

            $http.get('/api/public/motoristas/get/'+$rootScope.$stateParams.id_motorista).then(function (response) {
                
                $scope.motorista = response.data.motorista;
                $scope.motorista.motorista_beneficios = $.parseJSON($scope.motorista.motorista_beneficios);

                $scope.motorista.motorista_status = get_motorista_status_text($scope.motorista.motorista_status);
                $scope.motorista.motorista_person = get_motorista_person_text($scope.motorista.motorista_person);

            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.error;
            }).finally(function() {
                $rootScope.is_loading = false;
            });

        }

        function get_motorista_status_text(term){

            switch(term){
                case 'em-viagem':
                    return 'Em Viagem';
                    break;
                case 'na-empresa':
                    return 'Na Empresa';
                    break;
                case 'disposicao':
                    return 'Casa / A disposição';
                    break;
                case 'descansando':
                    return 'Casa / Descansando';
                    break;
                case 'ferias':
                    return 'Férias';
                    break;
                case 'afastado-saude':
                    return 'Afastado - Saúde';
                    break;
                case 'suspenso':
                    return 'Suspenso / Advertido';
                    break;
                case 'desligado':
                    return 'Desligado';
                    break;
                case 'servico-externo':
                    return 'Serviço Externo';
                    break;
                case 'outros':
                    return 'Outros';
                    break;
                default:
                    return term;
            }   

        }

        function get_motorista_person_text(term){

           switch(term){
                case 'colaborador':
                    return 'Colaborador';
                    break;
                case 'terceirizado':
                    return 'Terceirizado';
                    break;
                default:
                    return term;
            }  

        }

        get_motorista();

    }

})();