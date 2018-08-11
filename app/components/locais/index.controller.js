(function () {
    'use strict';

    angular
        .module('app')
        .controller('Locais.IndexController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {
        
        $scope.locais = {};

        $scope.currentPage = ($rootScope.$state.name == 'locais-paged' ? $rootScope.$stateParams.page : '1' );

        $scope.get_locais = function(){

            $rootScope.is_loading = true;

            $http.get('/api/public/locais/get?context='+$localStorage.currentEmpresaId+'&current_page='+$scope.currentPage).then(function (response) {

                $scope.locais = response.data;
                $scope.locais.config.current_page = parseInt($scope.locais.config.current_page);

                console.log( $scope.locais);

            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.error;
            }).finally(function() {
                $rootScope.is_loading = false;
            });
        }


        $rootScope.is_loading = true;
        $scope.get_locais();

    
        $scope.delete_local = function(id){
            if(confirm("Deseja excluir esse local?")){
                $rootScope.is_loading = true;
                $http.post('/api/public/locais/delete',{ id : id }).then(function (response) {
                    $scope.get_locais();

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