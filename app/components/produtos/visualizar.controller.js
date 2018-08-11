(function () {
    'use strict';

    angular
        .module('app')
        .controller('Produtos.VisualizarController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {
        
        
        function get_produto(){

                $http.get('/api/public/produtos/get/'+$rootScope.$stateParams.id_produto+'?context='+$localStorage.currentEmpresaId).then(function (response) {
                    $scope.produto = response.data.produto;
                    
                    console.log(  $scope.produto)

                }, function(response) {
                    $rootScope.is_error = true;
                    $rootScope.is_error_text = "Erro: " + response.data.error;
                }).finally(function() {
                    $rootScope.is_loading = false;
                });

        

        }

        get_produto();

    }

})();