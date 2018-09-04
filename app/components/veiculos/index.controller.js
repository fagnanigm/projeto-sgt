(function () {
    'use strict';

    angular
        .module('app')
        .controller('Veiculos.IndexController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {
        
        $scope.veiculos = {};

        $scope.currentPage = ($rootScope.$state.name == 'veiculos-paged' ? $rootScope.$stateParams.page : '1' );

        $scope.get_veiculos = function(){

            $rootScope.is_loading = true;

            $http.get('/api/public/veiculos/get?current_page=' + $scope.currentPage).then(function (response) {

                $scope.veiculos = response.data;
                // $scope.veiculos.config.current_page = parseInt($scope.veiculos.config.current_page);

                console.log( $scope.veiculos);

            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.error;
            }).finally(function() {
                $rootScope.is_loading = false;
            });
        }


        $rootScope.is_loading = true;
        $scope.get_veiculos();

    
        $scope.delete_veiculo = function(id){
            if(confirm("Deseja excluir esse veiculo?")){
                $rootScope.is_loading = true;
                $http.post('/api/public/veiculos/delete',{ id : id }).then(function (response) {
                    $scope.get_veiculos();

                    ngToast.create({
                        className: 'success',
                        content: 'Veículo excluído com sucesso'
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