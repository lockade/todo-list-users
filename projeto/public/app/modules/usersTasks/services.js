'use strict';

angular.module('UsersTasks')

    .factory('UsersTasksService',
        ['$http', '$cookieStore', '$rootScope',
            function ($http, $cookieStore, $rootScope) {
                var service = {};

                service.getUser = function (user_id, callback) {//para carregar nome do usuario na tela.
                    $http.get('/admin/users/' + user_id)
                        .success(function (response) {
                            callback(response);
                        })
                        .error(function (response) {
                            alert(response);
                        });
                }

                service.getTasksById = function (user_id, callback) {
                    $http.get('/admin/task/' + user_id)
                        .success(function (response) {
                            callback(response);
                        })
                        .error(function (response) {
                            alert(response);
                        });
                }


                service.saveTask = function(data, callback, callback_err) {
                    if(data?.id == null) {
                        service.addTask(data, callback, callback_err); //add
                    } else {
                        service.editTask(data, callback, callback_err); //edit
                    }
                }

                service.addTask = function (data, callback, callback_err) {

                    $http.post('/admin/task/add', data)
                        .success(function (response) {
                            callback(response);
                        })
                        .error(function (response) {
                            callback_err(response);
                        });
                }

                service.editTask = function (data, callback, callback_err) {

                    $http.put('/admin/task/edit', data)
                        .success(function (response) {
                            callback(response);
                        })
                        .error(function (response) {
                            callback_err(response);
                        });
                }

                service.excluir = function (task_id, callback) {
                    $http.delete('/admin/task/' + task_id)
                        .success(function (response) {
                            callback(response);
                        })
                        .error(function (response) {
                            alert(response?.msg);
                        });
                }

                return service;
            }])

