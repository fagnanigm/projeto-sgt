(function () {
    'use strict';

    angular
        .module('app')
        .controller('CondicoesPagamento.GerenciaController', Controller);

    function Controller($rootScope,$scope,$http,ngToast,$location,GlobalServices, $localStorage) {

        // Protect to change
        $scope.allow_change_page = false;
        $scope.$on('onBeforeUnload', function (e, confirmation) {
            confirmation.message = "Todos os dados que foram alterados serão perdidos.";
            e.preventDefault();
        });

        $scope.$on('$locationChangeStart', function (e, next, current) {
            if(!$scope.allow_change_page){
                if(!confirm("Todos os dados que foram alterados serão perdidos. Deseja prosseguir?")){
                    $rootScope.is_loading = false;
                    e.preventDefault();
                }  
            }
        });
        // Finish protect to change

        var vm = this;

        $scope.data = {};

        initController();

        function get_result(){

            if($rootScope.$state.name == "insert-condicoes-de-pagamento"){

                $scope.data = {}

                $rootScope.is_loading = false;

            }else{

                $http.get('/api/public/autorizacao-servico-prazo-pg/get/' + $rootScope.$stateParams.id_condicao).then(function (response) {

                    console.log(response);

                    $scope.data = response.data.data;

                }, function(response) {
                    $rootScope.is_error = true;
                    $rootScope.is_error_text = "Erro: " + response.data.error;
                }).finally(function() {
                    $rootScope.is_loading = false;
                });

            }

        }        

        function initController() {
        	get_result();
        }    

        vm.setItem = function(){

            var error = 0;
            
            $rootScope.is_loading = true;

            if($rootScope.$state.name == "insert-condicoes-de-pagamento"){

                $http.post('/api/public/autorizacao-servico-prazo-pg/insert', $scope.data).then(function (response) {
                    
                    if(response.data.result){

                        $scope.allow_change_page = true;

                        ngToast.create({
                            className: 'success',
                            content: "Condição cadastrada com sucesso!"
                        });

                        $location.path('/configuracoes/condicoes-de-pagamento');

                    }else{
                        ngToast.create({
                            className: 'danger',
                            content: response.data.error
                        });
                    }

                }, function(response) {
                    $rootScope.is_error = true;
                    $rootScope.is_error_text = "Erro: " + response.data.message;
                }).finally(function() {
                    $rootScope.is_loading = false;
                });

            }else{

                $http.post('/api/public/autorizacao-servico-prazo-pg/update', $scope.data ).then(function (response) {
                    
                    if(response.data.result){

                        $scope.allow_change_page = true;

                        ngToast.create({
                            className: 'success',
                            content: "Condição editada com sucesso!"
                        });

                        $location.path('/configuracoes/condicoes-de-pagamento');

                    }else{
                        ngToast.create({
                            className: 'danger',
                            content: response.data.error
                        });
                    }

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