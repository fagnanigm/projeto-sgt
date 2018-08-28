(function () {
    'use strict';

    angular
        .module('app')
        .controller('ModalInstanceCtrl', Controller);

    function Controller($rootScope, $uibModalInstance) {
        
        var $ctrl = this;

        $rootScope.is_modal_loading = false;
        
        $ctrl.close = function () {
            $uibModalInstance.dismiss('cancel');
        };

        $rootScope.closeModal = function(){
            $rootScope.is_modal_loading = false;
            $uibModalInstance.dismiss('cancel');
        }

    }

})();