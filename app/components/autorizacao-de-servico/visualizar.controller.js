(function () {
    'use strict';

    angular
        .module('app')
        .controller('As.VisualizarController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {
        
        
        function get_as(){

            $http.get('/api/public/as/get/'+$rootScope.$stateParams.id_as).then(function (response) {

                $scope.as = response.data.as;

                console.log($scope.as);

            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.error;
            }).finally(function() {
                $rootScope.is_loading = false;
            });

        }

        get_as();

    }

})();