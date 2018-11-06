(function () {
    'use strict';

    angular
        .module('app')
        .controller('Relatorios.IndexController', Controller);

    function Controller($scope, $rootScope, $http, ngToast) {
        var vm = this;

        $('#tabs-relatorios a').on('click', function (e) {
            e.preventDefault()
            $(this).tab('show')
        });

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
        $scope.init_cotacao_status = function(){

            var date = new Date();

            $scope.cotacao_status_filter = {
                status : '0',
                init : new Date(date.getFullYear(), date.getMonth(), 1),
                end : new Date(),
            }

        }

        $scope.submit_cotacao_status = function(){

            var filter = {
                cotacao_status: $scope.cotacao_status_filter.status,
                init: Math.floor($scope.cotacao_status_filter.init.getTime() / 1000),
                end: Math.floor($scope.cotacao_status_filter.end.getTime() / 1000),
            }

            if(filter.cotacao_status == '0'){
                ngToast.create({
                    className: 'danger',
                    content: "Selecione um status para a emissão desse relatório."
                });
                return;
            }

            $rootScope.is_loading = true;

            $http.post('/api/public/relatorios/cotacao/status', filter).then(function (response) {

                console.log(response);

                $rootScope.is_loading = false;

                if(response.data.result){
                    
                    window.open('/api/public' + response.data.file, '_blank');

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