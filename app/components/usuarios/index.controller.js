(function () {
    'use strict';

    angular
        .module('app')
        .controller('Usuarios.IndexController', Controller);

    function Controller($rootScope,$scope,$http,ngToast, PagerService) {
        var vm = this;

        vm.pager = {};
        vm.setPage = setPage;
        vm.paged_num = 8;
        vm.old_items = [];

        vm.usuarios = [];

        initController();

        $scope.$watch('search_field', function() {
            if($scope.search_field == null || $scope.search_field.length == 0){
                vm.items = vm.old_items;
                vm.old_items = [];
            }else{
                if(vm.old_items.length == 0){
                    vm.old_items = vm.items;
                }
                vm.items = vm.usuarios;
            }
        });

        function get_users(){
        	$http.post('http://model.exodocientifica.com.br/usuarios/read').then(function (response) {
                vm.usuarios = response.data;
                vm.setPage(1);
			}, function(response) {
				$rootScope.is_error = true;
				$rootScope.is_error_text = "Erro: " + response.data.message;
			}).finally(function() {
				$rootScope.is_loading = false;
			});
        }

        function initController() {
        	$rootScope.is_loading = true;
        	get_users();
        }

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

        function setPage(page) {
            if (page < 1 || page > vm.pager.totalPages) {
                return;
            }

            vm.pager = PagerService.GetPager(vm.usuarios.length, page, vm.paged_num);
            vm.items = vm.usuarios.slice(vm.pager.startIndex, vm.pager.endIndex + 1);
        }

    }

})();