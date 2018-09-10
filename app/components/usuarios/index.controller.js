(function () {
    'use strict';

    angular
        .module('app')
        .controller('Usuarios.IndexController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast) {
        
        $scope.users = {};

        $scope.currentPage = ($rootScope.$state.name == 'usuarios-paged' ? $rootScope.$stateParams.page : '1' );

        $scope.get_users = function(){

            $rootScope.is_loading = true;

            var rest_address = '/api/public/users/get';

            // Pagination
            rest_address = rest_address + '?current_page=' + $scope.currentPage;

            // Filter
            $.each($rootScope.get_filters, function(key, val){
                rest_address += '&' + key + '=' + val;
            });

        	$http.get(rest_address).then(function (response) {

                $scope.users = response.data;
                $scope.users.config.current_page = parseInt($scope.users.config.current_page);

			}, function(response) {
				$rootScope.is_error = true;
				$rootScope.is_error_text = "Erro: " + response.data.error;
			}).finally(function() {
				$rootScope.is_loading = false;
			});
        }


        $rootScope.is_loading = true;
        $scope.get_users();

    
        $scope.delete_user = function(id){
            if(confirm("Deseja excluir esse usuário?")){
                $rootScope.is_loading = true;
                $http.post('/api/public/users/delete',{ id : id }).then(function (response) {
                    $scope.get_users();

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

        $scope.open_change_password = function(user){
            
            $scope.current_user_password = user;
            $scope.current_user_password.new_pass = '';
            $scope.current_user_password.new_pass_repeat = '';

            $rootScope.openModal("/app/components/usuarios/alterar-senha.modal.html",false,$scope);
        }

        $scope.change_user_password = function(){

            $scope.is_modal_loading = true;

            var param = {
                id : $scope.current_user_password.id,
                pass : $scope.current_user_password.new_pass,
                pass_repeat : $scope.current_user_password.new_pass_repeat
            }

            if(param.pass.length < 6){
                ngToast.create({
                    className: 'danger',
                    content: 'A senha deve ter no mínimo 6 caracteres.'
                });
                $scope.is_modal_loading = false;
                return false;
            }

            if(param.pass != param.pass_repeat){
                ngToast.create({
                    className: 'danger',
                    content: 'As senhas não se conhecidem.'
                });
                $scope.is_modal_loading = false;
                return false;
            }

            $http.post('/api/public/users/forcechangepass', param).then(function (response) {

                if(response.data.result){
                    ngToast.create({
                        className: 'success',
                        content: 'Senha alterada com sucesso'
                    });
                    $rootScope.closeModal();
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
                $scope.is_modal_loading = false;
            });

        }

    

    }

})();