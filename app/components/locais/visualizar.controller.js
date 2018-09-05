(function () {
    'use strict';

    angular
        .module('app')
        .controller('Locais.VisualizarController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {
        
        
        function get_local(){

            $http.get('/api/public/locais/get/'+$rootScope.$stateParams.id_local).then(function (response) {
                $scope.local = response.data.local;
                
                console.log(  $scope.local)

            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.error;
            }).finally(function() {
                $rootScope.is_loading = false;
            });

        }

        get_local();

    }

})();