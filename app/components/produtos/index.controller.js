(function () {
    'use strict';

    angular
        .module('app')
        .controller('Produtos.IndexController', Controller);

    function Controller($rootScope,$scope,$http,ngToast, PagerService, $localStorage) {

        $scope.produtos = {};

        $scope.currentPage = ($rootScope.$state.name == 'produtos-paged' ? $rootScope.$stateParams.page : '1' );

        $scope.get_produtos = function(){

            $rootScope.is_loading = true;

            $http.get('/api/public/produtos/get?context='+$localStorage.currentEmpresaId+'&current_page='+$scope.currentPage).then(function (response) {

                $scope.produtos = response.data;
                $scope.produtos.config.current_page = parseInt($scope.produtos.config.current_page);

                console.log( $scope.produtos);

            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.error;
            }).finally(function() {
                $rootScope.is_loading = false;
            });
        }


        $rootScope.is_loading = true;
        $scope.get_produtos();

    
        $scope.delete_produto = function(id){
            if(confirm("Deseja excluir esse produto?")){
                $rootScope.is_loading = true;
                $http.post('/api/public/produtos/delete',{ id : id }).then(function (response) {
                    $scope.get_produtos();

                    ngToast.create({
                        className: 'success',
                        content: 'Produto excluído com sucesso'
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