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

            var rest_address = '/api/public/as/get';

            // Pagination
            rest_address = rest_address + '?current_page=' + $scope.currentPage;

            // Filter
            $.each($rootScope.get_filters, function(key, val){
                rest_address += '&' + key + '=' + val;
            });

            $http.get(rest_address).then(function (response) {

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
        get_vendedores();

        // Abrir todas as revisões
        $scope.open_revisoes = function($id){

            $scope.is_modal_loading = true;
            
            $rootScope.openModal("/app/components/autorizacao-de-servico/revisoes-list.modal.html",false,$scope);            

            $http.get('/api/public/as/revisoes/get/' + $id).then(function (response) {

                $scope.as_revisao = response.data;
                $scope.is_modal_loading = false;

            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.error;
            }).finally(function() {
                $scope.is_modal_loading = false;
            });           

        }

        function get_vendedores(){

            $http.get('/api/public/vendedores/get?getall=1').then(function (response) {
                $scope.vendedores = response.data.results;
            });

        }

    }

})();