(function () {
    'use strict';

    angular
        .module('app')
        .controller('As.IndexController', Controller);

    function Controller($rootScope,$scope,$http,ngToast,$location,GlobalServices, $localStorage) {
        
        $scope.autorizacoes = {};

        $scope.currentPage = ($rootScope.$state.name == 'autorizacao-de-servico-paged' ? $rootScope.$stateParams.page : '1' );

        $scope.get_autorizacoes = function(){

            $rootScope.is_loading = true;

            $http.get('/api/public/as/get?current_page='+$scope.currentPage).then(function (response) {

                $scope.autorizacoes = response.data;
                $scope.autorizacoes.config.current_page = parseInt($scope.autorizacoes.config.current_page);

            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.error;
            }).finally(function() {
                $rootScope.is_loading = false;
            });
        }


        $rootScope.is_loading = true;
        $scope.get_autorizacoes();

    }

})();