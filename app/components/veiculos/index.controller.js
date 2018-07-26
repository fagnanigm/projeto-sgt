(function () {
    'use strict';

    angular
        .module('app')
        .controller('Produtos.IndexController', Controller);

    function Controller($rootScope,$scope,$http,ngToast, PagerService) {
        var vm = this;

        vm.pager = {};
        vm.setPage = setPage;
        vm.paged_num = 8;
        vm.old_items = [];

        vm.products = [];

        initController();

        $scope.$watch('search_field', function() {
            if($scope.search_field == null || $scope.search_field.length == 0){
                vm.items = vm.old_items;
                vm.old_items = [];
            }else{
                if(vm.old_items.length == 0){
                    vm.old_items = vm.items;
                }
                vm.items = vm.products;
            }
        });

        function get_products(){
            $http.post('http://model.exodocientifica.com.br/produtos/read').then(function (response) {
                vm.products = response.data.data;
                vm.setPage(1);
            }, function(response) {
                $rootScope.is_error = true;
                $rootScope.is_error_text = "Erro: " + response.data.message;
            }).finally(function() {
                $rootScope.is_loading = false;
            });
        }

        function initController() {
            get_products();
        }

        function setPage(page) {
            if (page < 1 || page > vm.pager.totalPages) {
                return;
            }

            vm.pager = PagerService.GetPager(vm.products.length, page, vm.paged_num);
            vm.items = vm.products.slice(vm.pager.startIndex, vm.pager.endIndex + 1);
        }

        vm.delete_product = function(id){
            if(confirm("Deseja excluir esse produto?")){
                $rootScope.is_loading = true;
                $http.post('http://model.exodocientifica.com.br/produtos/remove',{ id : id }).then(function (response) {
                    get_products();

                    ngToast.create({
                        className: 'success',
                        content: 'Produto excluído com sucesso'
                    });

                }, function(response) {
                    $rootScope.is_error = true;
                    $rootScope.is_error_text = "Erro: " + response.data.message;
                }).finally(function() {
                    $rootScope.is_loading = false;
                });
            }
        }
    }

})();