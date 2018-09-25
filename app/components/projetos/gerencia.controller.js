(function () {
    'use strict';

    angular
        .module('app')
        .controller('Projetos.GerenciaController', Controller);

    function Controller($rootScope,$scope,$http,ngToast,$location,GlobalServices, $localStorage) {
        var vm = this;

        $scope.projeto = {};        

        initController();

        function get_projeto(){

            if($rootScope.$state.name == "insert-projeto"){

                $scope.projeto = {
                    id_author : $localStorage.currentUser.id,
                    id_empresa : $localStorage.currentEmpresaId, 
                    projeto_status : 'em-aberto'
                }

                $rootScope.is_loading = false;

            }else{

                $http.get('/api/public/projetos/get/' + $rootScope.$stateParams.id_projeto).then(function (response) {
                    $scope.projeto = response.data.projeto;

                }, function(response) {
                    $rootScope.is_error = true;
                    $rootScope.is_error_text = "Erro: " + response.data.error;
                }).finally(function() {
                    $rootScope.is_loading = false;
                });

            }

        }

        function initController() {
        	get_projeto();
        }
        
        
    }

})();