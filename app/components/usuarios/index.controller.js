(function () {
    'use strict';

    angular
        .module('app')
        .controller('Usuarios.IndexController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast) {
        
        $scope.users = {};

        $scope.currentPage = ($rootScope.$state.name == 'usuarios-paged' ? $rootScope.$stateParams.page : '1' );

        $scope.get_users = function(){

        	$http.get('/api/public/users/get?current_page='+$scope.currentPage).then(function (response) {

                $scope.users = response.data;
                $scope.users.config.current_page = parseInt($scope.users.config.current_page);
                console.log($scope.users);
                
			}, function(response) {
				$rootScope.is_error = true;
				$rootScope.is_error_text = "Erro: " + response.data.error;
			}).finally(function() {
				$rootScope.is_loading = false;
			});
        }


        $rootScope.is_loading = true;
        $scope.get_users();

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