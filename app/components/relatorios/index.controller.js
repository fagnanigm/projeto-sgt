(function () {
    'use strict';

    angular
        .module('app')
        .controller('Relatorios.IndexController', Controller);

    function Controller($scope, $rootScope, $http) {
        var vm = this;

        initController();

        function initController() {
            $rootScope.is_loading = false;
            get_categorias();
        }

        function get_categorias(){
            $http.get('/api/public/categorias/get?getall=1').then(function (response) {
                $scope.categorias = response.data.results;
            });
        }

        // Relatório de AS
        $scope.init_relatorio_as = function(){

            var date = new Date();

            $scope.relatorio_as_filter = {
                status : '0',
                init : new Date(date.getFullYear(), date.getMonth(), 1),
                end : new Date(),
                categoria : '0'
            }

        }

        $scope.submit_relatorio_as = function(){

            $rootScope.is_loading = true;

            var filter = {
                id_categoria: $scope.relatorio_as_filter.categoria,
                as_status: $scope.relatorio_as_filter.status,
                init: Math.floor($scope.relatorio_as_filter.init.getTime() / 1000),
                end: Math.floor($scope.relatorio_as_filter.end.getTime() / 1000),
            }

            $http.post('/api/public/relatorios/relatorio-as', filter).then(function (response) {

                $rootScope.is_loading = false;

                if(response.data.result){

                    location.href = '/api/public' + response.data.file;

                }else{
                    ngToast.create({
                        className: 'danger',
                        content: response.data.error
                    });
                }

            });

        }

    }

})();