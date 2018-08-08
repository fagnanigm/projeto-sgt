(function () {
    'use strict';

    angular
        .module('app')
        .controller('Configuracoes.IndexController', Controller);

    function Controller($scope, $rootScope, $http, ngToast, $location) {
        var vm = this;

        $scope.configuracoes = {};

        initController();

        function initController() {



        }

        $scope.load_config = function(meta_key){

            $rootScope.loading = true;

            $http.get('/api/public/configuracoes/get/' + meta_key).then(function (response) {

                $scope.configuracoes[meta_key] = response.data;
                $scope.configuracoes[meta_key].configuracao.meta_value = ($scope.configuracoes[meta_key].configuracao.meta_value.length > 0 ? $.parseJSON($scope.configuracoes[meta_key].configuracao.meta_value) : {} );
                
            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.error;
            }).finally(function() {
                $rootScope.is_loading = false;
            });

        }

        $scope.update_config = function(meta_key){

            $rootScope.loading = true;

            var param = {
                id : $scope.configuracoes[meta_key].configuracao.id,
                meta_value : JSON.stringify($scope.configuracoes[meta_key].configuracao.meta_value)
            }

            $http.post('/api/public/configuracoes/update', param ).then(function (response) {
                
               if(response.data.result){

                    ngToast.create({
                        className: 'success',
                        content: "Configuração salva com sucesso!"
                    });

                }else{
                    ngToast.create({
                        className: 'danger',
                        content: response.data.error
                    });
                }

            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.error;
            }).finally(function() {
                $rootScope.is_loading = false;
            });
    
            

        }

    }

})();