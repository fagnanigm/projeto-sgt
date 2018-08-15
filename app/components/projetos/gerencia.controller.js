(function () {
    'use strict';

    angular
        .module('app')
        .controller('Projetos.GerenciaController', Controller);

    function Controller($rootScope,$scope,$http,ngToast,$location,GlobalServices, $localStorage) {
        var vm = this;

        $scope.projeto = {};        

        initController();

        function get_projeto(){

            if($rootScope.$state.name == "insert-projeto"){

                $scope.projeto = {
                    id_author : $localStorage.currentUser.id,
                    id_empresa : $localStorage.currentEmpresaId, 
                    projeto_status : 'em-aberto'
                }

            }else{

                $http.get('/api/public/projetos/get/' + $rootScope.$stateParams.id_projeto + '?context=' + $localStorage.currentEmpresaId).then(function (response) {
                    $scope.projeto = response.data.projeto;
                    $scope.projeto.context = $localStorage.currentEmpresaId;
                }, function(response) {
                    $rootScope.is_error = true;
                    $rootScope.is_error_text = "Erro: " + response.data.error;
                }).finally(function() {
                    $rootScope.is_loading = false;
                });

            }

        }

        function initController() {
        	get_projeto();
        }


        vm.setProjeto = function(){

            // Tratamento 

            var error = 0;
            
            // Validação 
    
            if(error == 0){

                $rootScope.is_loading = true;

                if($rootScope.$state.name == "insert-projeto"){

                    $http.post('/api/public/projetos/insert', $scope.projeto).then(function (response) {
                        
                        if(response.data.result){

                            ngToast.create({
                                className: 'success',
                                content: "Projeto cadastrado com sucesso!"
                            });

                            $location.path('/projetos');

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

                    $http.post('/api/public/projetos/update', $scope.projeto ).then(function (response) {
                        
                        if(response.data.result){

                            ngToast.create({
                                className: 'success',
                                content: "Projetos editado com sucesso!"
                            });

                            $location.path('/projetos');

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