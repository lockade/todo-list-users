'use strict';

angular.module('Task')

    .factory('TaskService',
        ['$http', '$cookieStore', '$rootScope',
            function ($http, $cookieStore, $rootScope) {
                var service = {};

                service.getTask = function (callback) {

                    $http.get('/task')
                        .success(function (response) {
                            callback(response);
                        })
                        .error(function (response) {
                            alert(response?.msg);
                        });
                };

                service.concluirTask = function (id, callback) {

                    $http.get(`/task/concluir/${id}`)
                        .success(function (response) {
                            callback(response);
                        })
                        .error(function (response) {
                            alert(response?.msg);
                        });
                };

                service.checkIsAdmin = function () {
                    return $cookieStore.get('globals')?.currentUser.admin;
                }

                return service;
            }])

