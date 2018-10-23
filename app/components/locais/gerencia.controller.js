(function () {
    'use strict';

    angular
        .module('app')
        .controller('Locais.GerenciaController', Controller);

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

        $scope.local = {};

        initController();

        function get_local(){

            if($rootScope.$state.name == "insert-local"){

                $scope.local = {
                    id_author : $localStorage.currentUser.id,
                    local_estado : "",
                    local_pais : 'Brasil',
                    local_exterior : 'N',
                    local_cidade : ""
                }

                $scope.$watch('municipios', function() { 
                    $scope.local.local_cidade = '';
                });

                $rootScope.is_loading = false;

            }else{

                $http.get('/api/public/locais/get/' + $rootScope.$stateParams.id_local).then(function (response) {

                    $scope.local = response.data.local;
                    $rootScope.ufChange($scope.local.local_estado);

                }, function(response) {
                    $rootScope.is_error = true;
                    $rootScope.is_error_text = "Erro: " + response.data.error;
                }).finally(function() {
                    $rootScope.is_loading = false;
                });

            }

        }        

        function initController() {
            $rootScope.get_ufs();
        	get_local();
        }    

        vm.setLocal = function(){

            var error = 0;
            
            // Validação 
            if($scope.local.local_estado == '0'){
                ngToast.create({
                    className: 'danger',
                    content: "Estado inválido"
                });
                error++;
                return;
            }

            if($scope.local.local_cidade == '0'){
                ngToast.create({
                    className: 'danger',
                    content: "Cidade inválida"
                });
                error++;
                return;
            }

            if($scope.local.local_pais == '0'){
                ngToast.create({
                    className: 'danger',
                    content: "País inválido"
                });
                error++;
                return;
            }
            
            if(error == 0){

                $rootScope.is_loading = true;

                if($scope.local.local_exterior == 'N'){
                    $scope.local.local_estado_ibge = $('select[name="local_estado"] option:selected').data('ibge-code');
                    $scope.local.local_cidade_ibge = $('select[name="local_cidade"] option:selected').data('ibge-code');
                }

                $scope.local.local_pais_ibge = $('select[name="local_pais"] option:selected').data('ibge-code');

                if($rootScope.$state.name == "insert-local"){

                    $http.post('/api/public/locais/insert', $scope.local).then(function (response) {
                        
                        if(response.data.result){

                            $scope.allow_change_page = true;

                            ngToast.create({
                                className: 'success',
                                content: "Local cadastrado com sucesso!"
                            });

                            $location.path('/locais');

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

                    $http.post('/api/public/locais/update', $scope.local ).then(function (response) {
                        
                        if(response.data.result){

                            $scope.allow_change_page = true;

                            ngToast.create({
                                className: 'success',
                                content: "Local editado com sucesso!"
                            });

                            $location.path('/locais');

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

        // Listagem para o mapa

        $scope.$watch('local', function() {

            $scope.local.local_google_address = false;

            if(
                $scope.local.local_cep.length == 0 ||
                $scope.local.local_logradouro.length == 0 ||
                $scope.local.local_numero.length == 0 || 
                $scope.local.local_estado.length == 0 ||
                $scope.local.local_cidade.length == 0                 
            ){
                return;
            }

            $scope.local.local_google_address = 
                $scope.local.local_logradouro + ' ' + 
                $scope.local.local_numero + ' ' + 
                $scope.local.local_cidade + ' ' +
                $scope.local.local_estado + ' ' + 
                $scope.local.local_cep;

                get_google_map();
            
        }, true);
        
        function get_google_map() {
            
            

            

        }

    }

})();