(function () {
    'use strict';

    angular
        .module('app')
        .controller('GlobalServicesCtrl', Controller);

    function Controller($rootScope, $location, AuthenticationService) {
        var gl = this;

        initController();

        function initController() {
            // reset login status
            //AuthenticationService.Logout();
            if($rootScope.is_logged){
                //$location.path('/dashboard');
            }
        };

        gl.logout = function() {
            AuthenticationService.Logout();
            $location.path('/');
        };
    }

})();