(function () {
    'use strict';

    angular
        .module('app')
        .controller('As.IndexController', Controller);

    function Controller($rootScope,$scope,$http,ngToast,$location,GlobalServices, $localStorage) {
        
        $scope.autorizacoes = {};

        $scope.currentPage = ($rootScope.$state.name == 'autorizacoes-paged' ? $rootScope.$stateParams.page : '1' );

        $scope.get_autorizacoes = function(){

            $rootScope.is_loading = true;

            $http.get('/api/public/as/get?context='+$localStorage.currentEmpresaId+'&current_page='+$scope.currentPage).then(function (response) {

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

    
        $scope.delete_local = function(id){
            if(confirm("Deseja excluir esse local?")){
                $rootScope.is_loading = true;
                $http.post('/api/public/autorizacoes/delete',{ id : id }).then(function (response) {
                    $scope.get_autorizacoes();

                    ngToast.create({
                        className: 'success',
                        content: 'Local excluído com sucesso'
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