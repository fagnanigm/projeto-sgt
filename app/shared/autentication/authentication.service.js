(function () {
    'use strict';

    angular
        .module('app')
        .factory('AuthenticationService', Service);

    function Service($http, $localStorage, $rootScope) {
        var service = {};

        service.Login = Login;
        service.Logout = Logout;

        return service;

        function Login(username, password, callback) {

            $http.post('/api/public/users/login', { email: username, password: password })
                .success(function (response) {


                    if (response.result) {
                        // store username and token in local storage to keep user logged in between page refreshes
                        $localStorage.currentUser = { username: username, token: response.token };

                        // add jwt token to auth header for all requests made by the $http service
                        $http.defaults.headers.common.Authorization = 'Bearer ' + response.token;

                        // execute callback with true to indicate successful login
                        callback(true);

                        // define systm status
                        $rootScope.is_logged = true;

                    } else {
                        // execute callback with false to indicate failed login
                        callback(false);
                        $rootScope.is_logged = false;
                    }

                    
                });

        }

        function Logout() {
            // remove user from local storage and clear http auth header
            delete $localStorage.currentUser;
            $http.defaults.headers.common.Authorization = '';
            $rootScope.is_logged = false;
        }
    }
})();