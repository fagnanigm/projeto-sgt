(function () {
    'use strict';

    angular
        .module('app')
        .controller('Motoristas.GerenciaController', Controller);

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

        $scope.motorista = {};

        $scope.beneficios = [
            "NR10",
            "NR35",
            "CI - C. Indivis.",
            "CODESP",
            "Credenciado",
            "Vale pedágio",
            "CIOT"
        ];


        initController();

        function get_motorista(){

            if($rootScope.$state.name == "insert-motorista"){

                $scope.motorista = {
                    id_author : $localStorage.currentUser.id,
                    id_filial : '0',
                    motorista_status : '0',
                    motorista_person : 'colaborador',
                    motorista_cnh_categoria : '0'
                }

            }else{

                $http.get('/api/public/motoristas/get/' + $rootScope.$stateParams.id_motorista).then(function (response) {
                    $scope.motorista = response.data.motorista;

                    if($scope.motorista.motorista_data_nascimento.length > 0){
                        $scope.motorista.motorista_data_nascimento_obj = new Date($scope.motorista.motorista_data_nascimento);
                    }

                    if($scope.motorista.motorista_cnh_validade.length > 0){
                        $scope.motorista.motorista_cnh_validade_obj = new Date($scope.motorista.motorista_cnh_validade);
                    }

                    var beneficios = $.parseJSON($scope.motorista.motorista_beneficios);
                    $scope.motorista.motorista_beneficios_checked = {};

                    $.each(beneficios, function(key, val){
                        $scope.motorista.motorista_beneficios_checked[val.key] = true;
                    });

                }, function(response) {
                    $rootScope.is_error = true;
                    $rootScope.is_error_text = "Erro: " + response.data.error;
                }).finally(function() {
                    $rootScope.is_loading = false;
                });

            }

        }

        function initController() {
            get_empresas();
        	get_motorista();
        }


        vm.setMotorista = function(){

            // Tratamento 
            $scope.motorista.motorista_beneficios = [];

            $.each($scope.motorista.motorista_beneficios_checked, function(key, val){

                if(val){
                    $scope.motorista.motorista_beneficios.push({
                        key : key,
                        beneficio : $scope.beneficios[key]
                    })
                }

            });

            $scope.motorista.motorista_beneficios = JSON.stringify($scope.motorista.motorista_beneficios);

            if($scope.motorista.motorista_cnh_validade_obj == null){
                delete $scope.motorista.motorista_cnh_validade_obj;
            }else{
                $scope.motorista.motorista_cnh_validade = Math.floor($scope.motorista.motorista_cnh_validade_obj.getTime() / 1000);
            }

            if($scope.motorista.motorista_data_nascimento_obj == null){
                ngToast.create({
                    className: 'danger',
                    content: "Escolha a data de nascimento deste motorista"
                });
                return; 
            }else{
                $scope.motorista.motorista_data_nascimento = Math.floor($scope.motorista.motorista_data_nascimento_obj.getTime() / 1000);
            }

            // Validação
            if($scope.motorista.id_filial == '0'){
                ngToast.create({
                    className: 'danger',
                    content: "Escolha uma filial para este motorista"
                });
                return;
            }

            if($scope.motorista.motorista_status == '0'){
                ngToast.create({
                    className: 'danger',
                    content: "Escolha um status para este motorista"
                });
                return;
            }


            $rootScope.is_loading = true;

            if($rootScope.$state.name == "insert-motorista"){

                $http.post('/api/public/motoristas/insert', $scope.motorista).then(function (response) {
                    
                    if(response.data.result){

                        $scope.allow_change_page = true;

                        ngToast.create({
                            className: 'success',
                            content: "Motorista cadastrado com sucesso!"
                        });

                        $location.path('/motoristas');

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

                $http.post('/api/public/motoristas/update', $scope.motorista ).then(function (response) {

                    if(response.data.result){

                        $scope.allow_change_page = true;

                        ngToast.create({
                            className: 'success',
                            content: "Motorista editado com sucesso!"
                        });

                        $location.path('/motoristas');

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


        function get_empresas(){

            $http.get('/api/public/empresas/get?getall=1').then(function (response) {
                $scope.empresas = response.data.results;
            }).finally(function() {
                $rootScope.is_loading = false;
            });

        }



        
    }

})();