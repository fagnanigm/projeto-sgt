(function () {
    'use strict';

    angular
        .module('app')
        .controller('Login.IndexController', Controller);

    function Controller($rootScope, $location, AuthenticationService) {
        var vm = this;

        vm.login = login;

        initController();

        function initController() {
            // reset login status
            if($rootScope.is_logged && $location.path() == '/'){
                $location.path('/dashboard');
            }
        };

        function login() {
            $rootScope.is_loading = true;
            AuthenticationService.Login(vm.username, vm.password, function (result) {

                if (result === true) {
                    $location.path('/seleciona-empresa'); 
                } else {
                    vm.error = 'E-mail ou senha estão incorretos';
                }
                $rootScope.is_loading = false;
            });
        };
    }

})();