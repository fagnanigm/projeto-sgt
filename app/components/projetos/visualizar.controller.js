(function () {
    'use strict';

    angular
        .module('app')
        .controller('Projetos.VisualizarController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {
        
        
        function get_projeto(){

            $http.get('/api/public/projetos/get/'+$rootScope.$stateParams.id_projeto).then(function (response) {

                $scope.projeto = response.data.projeto;

                get_list_as();

            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.error;
            });

        }

        function get_list_as(){

            $http.get('/api/public/as/getbyproject/'+$rootScope.$stateParams.id_projeto).then(function (response) {

                $scope.projeto.list_as = response.data.results;

            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.error;
            }).finally(function() {
                $rootScope.is_loading = false;
            });

        }

        get_projeto();

        $scope.change_status = function($status){

            if(confirm("Deseja alterar o status desse projeto?")){

                var param = {
                    id : $scope.projeto.id,
                    status : $status
                }

                $rootScope.is_loading = true;

                $http.post('/api/public/projetos/changestatus', param ).then(function (response) {

                    if(response.data.result){
                        location.reload();
                    }else{

                        ngToast.create({
                            className: 'danger',
                            content: response.data.error
                        });

                    }

                }, function(response) {
                    $rootScope.is_error = true;
                    $rootScope.is_error_text = "Erro: " + response.data.error;
                });

            }

        }

    }



})();