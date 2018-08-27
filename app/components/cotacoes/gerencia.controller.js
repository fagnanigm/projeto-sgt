(function () {
    'use strict';

    angular
        .module('app')
        .controller('Cotacoes.GerenciaController', Controller);

    function Controller($rootScope,$scope,$http,ngToast,$location,GlobalServices, $localStorage) {
        var vm = this;

        $scope.cotacao = {};

        initController();

        function get_cotacao(){

            if($rootScope.$state.name == "insert-cotacao"){

                $scope.cotacao = {
                    id_author : $localStorage.currentUser.id,
                    id_empresa : $localStorage.currentEmpresaId, 
                }

            }else{

                $http.get('/api/public/cotacoes/get/' + $rootScope.$stateParams.id_cotacao + '?context=' + $localStorage.currentEmpresaId).then(function (response) {
                    
                    $scope.cotacao = response.data.cotacao;
                    $scope.cotacao.context = $localStorage.currentEmpresaId;

                }, function(response) {
                    $rootScope.is_error = true;
                    $rootScope.is_error_text = "Erro: " + response.data.error;
                }).finally(function() {
                    $rootScope.is_loading = false;
                });

            }

        }        

        function initController() {
        	get_cotacao();
        }        

        vm.setCotacao = function(){

            var error = 0;
            
            // Validação 
            if($scope.cotacao.cotacao_estado == '0'){
                ngToast.create({
                    className: 'danger',
                    content: "Estado inválido"
                });
                error++;
                return;
            }
    
            if(error == 0){

                $rootScope.is_loading = true;

                if($rootScope.$state.name == "insert-cotacao"){

                    $http.post('/api/public/cotacoes/insert', $scope.cotacao).then(function (response) {

                        console.log(response);
                        
                        if(response.data.result){

                            ngToast.create({
                                className: 'success',
                                content: "Cotação cadastrado com sucesso!"
                            });

                            $location.path('/cotacoes');

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

                    console.log($scope.cotacao);

                    $http.post('/api/public/cotacoes/update', $scope.cotacao ).then(function (response) {


                        
                        if(response.data.result){

                            ngToast.create({
                                className: 'success',
                                content: "Cotações editado com sucesso!"
                            });

                            $location.path('/cotacoes');

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



        
    }

})();