'use strict';

angular.module('Users')

    .factory('UsersService',
        ['$http', '$cookieStore', '$rootScope',
            function ($http, $cookieStore, $rootScope) {
                var service = {};

                service.getUsers = function (callback) {

                    $http.get('/admin/users')
                        .success(function (response) {
                            callback(response);
                        })
                        .error(function (response) {
                            alert(response?.msg);
                        });
                };

                service.getUser = function (user_id, callback) {
                    $http.get('/admin/users/' + user_id)
                        .success(function (response) {
                            callback(response);
                        })
                        .error(function (response) {
                            alert(response?.msg);
                        });
                }

                service.saveUser = function(data, callback, callback_err) {
                    if(data?.id == null) {
                        service.addUser(data, callback, callback_err); //add
                    } else {
                        service.editUser(data, callback, callback_err); //edit
                    }
                }

                service.addUser = function (data, callback, callback_err) {

                    $http.post('/admin/users/add', data)
                        .success(function (response) {
                            callback(response);
                        })
                        .error(function (response) {
                            callback_err(response);
                        });
                }

                service.editUser = function (data, callback, callback_err) {

                    $http.put('/admin/users/edit', data)
                        .success(function (response) {
                            callback(response);
                        })
                        .error(function (response) {
                            callback_err(response);
                        });
                }

                service.excluir = function (user_id, callback) {
                    $http.delete('/admin/users/' + user_id)
                        .success(function (response) {
                            callback(response);
                        })
                        .error(function (response) {
                            alert(response?.msg);
                        });
                }

                return service;
            }])
