'use strict';

angular.module('Authentication')

.controller('LoginController',
    ['$scope', '$rootScope', '$location', 'AuthenticationService',
    function ($scope, $rootScope, $location, AuthenticationService) {
        // reset login status
        AuthenticationService.ClearCredentials();

        $scope.login = function () {
            $scope.dataLoading = true;
            AuthenticationService.Login($scope.username, $scope.password, function (response) {
                console.log(response);
                if (response.error === false) {
                    AuthenticationService.SetCredentials($scope.username, $scope.password, response.admin);
                    if(response.admin) {
                        $location.path('/admin');
                    } else {
                        $location.path('/task');
                    }
                } else {
                    $scope.error = response.msg;
                    $scope.dataLoading = false;
                }
            });
        };
    }]);