(function () {
    'use strict';

    angular
        .module('app')
        .controller('GlobalServicesCtrl', Controller);

    function Controller($rootScope, $http, $location, $localStorage, AuthenticationService, $uibModal, $document) {
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

        $rootScope.get_ufs = function(){
            $http.get('/api/public/localidades/get/ufs').then(function (response) {
                $rootScope.ufs = response.data.results;
            });
        }

        $rootScope.ufChange = function(term, obj){
            $rootScope.is_loading = true;
            $http.get('/api/public/localidades/get/municipios/'+term).then(function (response) {
                $rootScope.municipios = response.data.municipios;
                $rootScope.is_loading = false;
            });
        }


        // Modal
        $rootScope.openModal = function (template, size, $scope) {

            size = (typeof(size) == 'undefined' || size == false ? 'modal-sm' : size );

            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: template,
                controller: 'ModalInstanceCtrl',
                controllerAs: '$ctrl',
                scope: $scope,
                size: size
            });

        };

    }

})();