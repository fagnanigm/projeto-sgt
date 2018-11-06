(function () {
    'use strict';

    angular
        .module('app')
        .controller('Usuarios.VisualizaController', Controller);

    function Controller($rootScope,$scope,$http,$location,ngToast) {
         

        initController();

        function initController() {
            
            $rootScope.is_loading = false;

        }    

    }

})();