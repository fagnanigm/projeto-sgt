(function () {
    'use strict';

    angular
        .module('app')
        .controller('Projetos.VisualizarController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {
        
        
        function get_projeto(){

            $http.get('/api/public/projetos/get/'+$rootScope.$stateParams.id_projeto+'?context='+$localStorage.currentEmpresaId).then(function (response) {
                $scope.projeto = response.data.projeto;

            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.error;
            }).finally(function() {
                $rootScope.is_loading = false;
            });

        }

        get_projeto();

    }

})();