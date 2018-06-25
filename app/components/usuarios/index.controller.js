(function () {
    'use strict';

    angular
        .module('app')
        .controller('Usuarios.IndexController', Controller);

    function Controller($rootScope,$scope,$http,ngToast) {
        
        initController();

        $scope.users = {};

        function get_users(){
        	$http.get('/api/public/users/get').then(function (response) {

                $scope.users = response.data;
                console.log($scope.users);
                
			}, function(response) {
				$rootScope.is_error = true;
				$rootScope.is_error_text = "Erro: " + response.data.error;
			}).finally(function() {
				$rootScope.is_loading = false;
			});
        }

        function initController() {
        	$rootScope.is_loading = true;
        	get_users();
        }

        /*
        vm.delete_user = function(id){
            if(confirm("Deseja excluir esse usuário?")){
                $rootScope.is_loading = true;
                $http.post('http://model.exodocientifica.com.br/usuarios/remove',{ id : id }).then(function (response) {
                    get_users();

                    ngToast.create({
                        className: 'success',
                        content: 'Usuário excluído com sucesso'
                    });

                }, function(response) {
                    $rootScope.is_error = true;
                    $rootScope.is_error_text = "Erro: " + response.data.message;
                }).finally(function() {
                    $rootScope.is_loading = false;
                });
            }
        }

        */

    }

})();