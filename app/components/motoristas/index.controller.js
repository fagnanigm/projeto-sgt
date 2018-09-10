(function () {
    'use strict';

    angular
        .module('app')
        .controller('Motoristas.IndexController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {
        
        $scope.motoristas = {};

        $scope.currentPage = ($rootScope.$state.name == 'motoristas-paged' ? $rootScope.$stateParams.page : '1' );

        $scope.get_motoristas = function(){

            $rootScope.is_loading = true;

            var rest_address = '/api/public/motoristas/get';

            // Pagination
            rest_address = rest_address + '?current_page=' + $scope.currentPage;

            // Filter
            $.each($rootScope.get_filters, function(key, val){
                rest_address += '&' + key + '=' + val;
            });

            $http.get(rest_address).then(function (response) {

                $scope.motoristas = response.data;
                $scope.motoristas.config.current_page = parseInt($scope.motoristas.config.current_page);

            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.error;
            }).finally(function() {
                $rootScope.is_loading = false;
            });
        }


        $rootScope.is_loading = true;
        $scope.get_motoristas();

    
        $scope.delete_motorista = function(id){
            if(confirm("Deseja excluir esse motorista?")){
                $rootScope.is_loading = true;
                $http.post('/api/public/motoristas/delete',{ id : id }).then(function (response) {
                    $scope.get_motoristas();

                    ngToast.create({
                        className: 'success',
                        content: 'Motorista excluído com sucesso'
                    });

                }, function(response) {
                    $rootScope.is_error = true;
                    $rootScope.is_error_text = "Erro: " + response.data.message;
                }).finally(function() {
                    $rootScope.is_loading = false;
                });
            }
        }

    

    }

})();