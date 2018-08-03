(function () {
    'use strict';

    angular
        .module('app', ['ui.router', 'ngMessages', 'ngStorage', 'ngAnimate','ngToast','ngMask'])
        .run(run)
        .animation('.yAnimate',animation)
        .factory('PagerService', PagerService)
        .filter('range', rangeService);

    function run($rootScope, $http, $location, $localStorage, $state, $stateParams,AuthenticationService, GlobalServices) {

        $rootScope.$state = $state;
        $rootScope.$stateParams = $stateParams;
        $http.defaults.headers.common.Type = 'admin';

        // keep user logged in after page refresh
        if($location.path() != '/'){
            $rootScope.is_logged = true;
        }else{
            $rootScope.is_logged = false;
        }
        
        if ($localStorage.currentUser) {
            $http.defaults.headers.common.Authorization = 'Bearer ' + $localStorage.currentUser.token;
        }else{
            $rootScope.is_logged = false;
        }
        
        // redirect to login page if not logged in and trying to access a restricted page
        $rootScope.$on('$locationChangeStart', function (event, next, current) {
            
            var publicPages = ['/login'];
            var restrictedPage = publicPages.indexOf($location.path()) === -1;
            if (restrictedPage && !$localStorage.currentUser) {
                $location.path('/');
            }else{  
                $rootScope.is_loading = true;
                $http.get("/api/public/users/get/"+$localStorage.currentUser.id,).then(function (response) {
                    
                    if(!response.data.result){
                        AuthenticationService.Logout();
                        $location.path('/');
                    }else{
                        $rootScope.is_loading = false;
                        $rootScope.logged_user = response.data.user;
                        $rootScope.is_logged = true;

                        // Converte algumas informações
                        $rootScope.logged_user.phone1 = GlobalServices.phone_parser({ ddd : $rootScope.logged_user.ddd_phone_01, number : $rootScope.logged_user.phone_01 }, 'implode');
                        $rootScope.logged_user.phone2 = GlobalServices.phone_parser({ ddd : $rootScope.logged_user.ddd_phone_02, number : $rootScope.logged_user.phone_02 }, 'implode');

                        console.log($rootScope.logged_user);
                    }
                });
            }
            
        });

        // Refresh state name
        $rootScope.$on('$stateChangeSuccess', function(event, toState, toParams, fromState, fromParams){
            $rootScope.$state = toState;
            $rootScope.$stateParams = toParams;
            $rootScope.is_error = false;

            window.scrollTo(0, 0);

            /*
            if($rootScope.is_logged && $rootScope.$state.name == 'login'){
                $location.path('/dashboard');
            }
            */

        }); 

        
    }

    function animation(){
        return {
            enter : function(element, done){

                TweenMax.from(element, 1, {alpha:0, x:200, ease:Power4.easeOut, onComplete:done});
                
            },
            leave : function(element, done){
                TweenMax.to(element, 0, {alpha:0, x:0, ease:Power4.easeOut, onComplete:done});
            }
        }
    }

    function PagerService() {
        // service definition
        var service = {};

        service.GetPager = GetPager;

        return service;

        // service implementation
        function GetPager(totalItems, currentPage, pageSize) {
            // default to first page
            currentPage = currentPage || 1;

            // default page size is 10
            pageSize = pageSize || 10;

            // calculate total pages
            var totalPages = Math.ceil(totalItems / pageSize);

            var startPage, endPage;
            if (totalPages <= 10) {
                // less than 10 total pages so show all
                startPage = 1;
                endPage = totalPages;
            } else {
                // more than 10 total pages so calculate start and end pages
                if (currentPage <= 6) {
                    startPage = 1;
                    endPage = 10;
                } else if (currentPage + 4 >= totalPages) {
                    startPage = totalPages - 9;
                    endPage = totalPages;
                } else {
                    startPage = currentPage - 5;
                    endPage = currentPage + 4;
                }
            }

            // calculate start and end item indexes
            var startIndex = (currentPage - 1) * pageSize;
            var endIndex = Math.min(startIndex + pageSize - 1, totalItems - 1);

            // create an array of pages to ng-repeat in the pager control
            var pages = [];
            var page_num;

            for(page_num = 1; page_num <= totalPages; page_num++){
                pages.push(page_num);
            }

            // return object with all pager properties required by the view
            return {
                totalItems: totalItems,
                currentPage: currentPage,
                pageSize: pageSize,
                totalPages: totalPages,
                startPage: startPage,
                endPage: endPage,
                startIndex: startIndex,
                endIndex: endIndex,
                pages: pages
            };
        }
    }


    angular.module('app').config(['ngToastProvider', function(ngToast) {
        ngToast.configure({
            animation : 'fade'
        });
    }]);


    function rangeService(){
        return function(input, total) {
            total = parseInt(total);
            for (var i=1; i<=total; i++)
                input.push(i);
                return input;
        };
    };

})();