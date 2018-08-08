(function () {
    'use strict';

    angular
        .module('app')
        .controller('GlobalServicesCtrl', Controller);

    function Controller($rootScope, $http, $location, $localStorage, AuthenticationService) {
        var gl = this;

        initController();

        function initController() {
            // reset login status
            //AuthenticationService.Logout();
            if($rootScope.is_logged){
                //$location.path('/dashboard');
            }

            // Context Selected


        };

        gl.logout = function() {
            AuthenticationService.Logout();
            $location.path('/');
        };

        gl.is_empresa_selected = function(){

            if(typeof($localStorage.currentEmpresaId) == 'undefined'){
                return false;
            }

            return true;

        }


        if(gl.is_empresa_selected()){

            $http.get('/api/public/empresas/get/'+$localStorage.currentEmpresaId).then(function (response) {
                $rootScope.selectedEmpresa = response.data.empresa;
            }, function(response) {
                $rootScope.is_error_text = "Erro: " + response.data.error;
            });

        }



    }

})();