(function () {
    'use strict';

    angular
        .module('app')
        .controller('Clientes.ImportController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast,$localStorage) {

        initController();

        $scope.import = false;
        
        $scope.importar = function(empresa){

            if(confirm("O precesso levará aproximadamente de 10 a 20 minutos, deseja continuar?")){

                $rootScope.is_loading = true;

                var param = {
                    id_empresa : empresa.id,
                    app_key : empresa.empresa_app_key,
                    app_secret : empresa.empresa_app_secret
                }

                $http.post('/api/public/clientes/importOmie', param).then(function (response) {
                    location.reload();
                    $scope.import = response.data;
                }, function(response) {
                    console.log(response)
                    $rootScope.is_error = true;
                    $rootScope.is_error_text = "Erro: " + response.data.error;
                }).finally(function() {
                    $rootScope.is_loading = false;
                });              

            }

        }

        function initController(){
            $scope.empresas = [];
            get_empresas();
        }

        function get_empresas(){
            $http.get('/api/public/clientes/import/getempresas').then(function (response) {
                
                $scope.empresas = response.data.results;

                $scope.geral_cliente_ativos = 0;
                $scope.geral_qtd = 0;

                $.each($scope.empresas, function(key,val){
                    $scope.geral_cliente_ativos += parseInt(val.clientes_ativos);
                    $scope.geral_qtd += parseInt(val.qtd_importacoes);
                });

            }).finally(function() {
                $rootScope.is_loading = false;
            });
        }

    }

})();