(function () {
    'use strict';

    angular
        .module('app')
        .controller('Cotacoes.IndexController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {
        
        $scope.cotacoes = {};

        $scope.currentPage = ($rootScope.$state.name == 'cotacoes-paged' ? $rootScope.$stateParams.page : '1' );

        $scope.get_cotacoes = function(){

            $rootScope.is_loading = true;

            var rest_address = '/api/public/cotacoes/get';

            // Pagination
            rest_address = rest_address + '?current_page=' + $scope.currentPage;

            // Filter
            $.each($rootScope.get_filters, function(key, val){
                rest_address += '&' + key + '=' + val;
            });

        	$http.get(rest_address).then(function (response) {

                $scope.cotacoes = response.data;
                $scope.cotacoes.config.current_page = parseInt($scope.cotacoes.config.current_page);

			}, function(response) {
				$rootScope.is_error = true;
				$rootScope.is_error_text = "Erro: " + response.data.error;
			}).finally(function() {
				$rootScope.is_loading = false;
			});
        }


        $rootScope.is_loading = true;
        $scope.get_cotacoes();

        // Abrir todas as revisões
        $scope.open_revisoes = function($id){

            $scope.is_modal_loading = true;
            
            $rootScope.openModal("/app/components/cotacoes/revisoes-list.modal.html",false,$scope);

            $http.get('/api/public/cotacoes/revisoes/get/' + $id).then(function (response) {

                $scope.cotacao_revisao = response.data;

                console.log($scope.cotacao_revisao);

                $scope.is_modal_loading = false;

            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.error;
            }).finally(function() {
                $scope.is_modal_loading = false;
            });

           

        }
    

    }

})();