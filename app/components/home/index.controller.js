(function () {
    'use strict';

    angular
        .module('app')
        .controller('Home.IndexController', Controller);

    function Controller($rootScope) {
        var vm = this;

        initController();

        function initController() {
            $rootScope.is_loading = false;

        }
    }

})();