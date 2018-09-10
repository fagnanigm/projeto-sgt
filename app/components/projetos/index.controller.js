(function () {
    'use strict';

    angular
        .module('app')
        .controller('Projetos.IndexController', Controller);

        function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {
        
        $scope.projetos = {};

        $scope.currentPage = ($rootScope.$state.name == 'projetos-paged' ? $rootScope.$stateParams.page : '1' );

        $scope.get_projetos = function(){

            $rootScope.is_loading = true;

            $http.get('/api/public/projetos/get?context='+$localStorage.currentEmpresaId+'&current_page='+$scope.currentPage).then(function (response) {

                $scope.projetos = response.data;
                $scope.projetos.config.current_page = parseInt($scope.projetos.config.current_page);

            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.error;
            }).finally(function() {
                $rootScope.is_loading = false;
            });
        }


        $rootScope.is_loading = true;
        $scope.get_projetos();

    
        $scope.delete_projeto = function(id){
            if(confirm("Deseja excluir esse projeto?")){
                $rootScope.is_loading = true;
                $http.post('/api/public/projetos/delete',{ id : id }).then(function (response) {
                    $scope.get_projetos();

                    ngToast.create({
                        className: 'success',
                        content: 'Projeto excluído com sucesso'
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