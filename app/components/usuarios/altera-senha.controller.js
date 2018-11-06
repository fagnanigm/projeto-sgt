(function () {
    'use strict';

    angular
        .module('app')
        .controller('Usuarios.AlteraSenhaController', Controller);

    function Controller($rootScope,$scope,$http,$location,$localStorage,ngToast) {

        var vm = this;

        $scope.user = {
            id : $localStorage.currentUser.id   
        };

        $rootScope.is_loading = false;
        
        vm.user_change_password = function(){
        
            $rootScope.is_loading = true;

            console.log($scope.user);

            if($scope.user.new_password.length < 6){
                ngToast.create({
                    className: 'danger',
                    content: 'A senha deve ter no mínimo 6 caracteres.'
                });
                $rootScope.is_loading = false;
                return false;
            }

            if($scope.user.new_password != $scope.user.new_password_2){
                ngToast.create({
                    className: 'danger',
                    content: 'As senhas não se conhecidem.'
                });
                $rootScope.is_loading = false;
                return false;
            }

            var param = {
                active_pass : $scope.user.active_password,
                pass : $scope.user.new_password,
                id : $scope.user.id
            }

            $http.post('/api/public/users/changepass', param ).then(function (response) {

                if(response.data.result){
                    ngToast.create({
                        className: 'success',
                        content: 'Senha alterada com sucesso'
                    });
                    
                    setTimeout(function(){ location.reload(); },1000);
                    
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

})();