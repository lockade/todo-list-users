'use strict';

angular.module('UsersTasks')

.controller('UsersTasksController',
    ['$scope', '$rootScope', '$routeParams', 'UsersTasksService',
    function ($scope, $rootScope, $routeParams, UsersTasksService) {
        $scope.user_id = $routeParams.user_id;

        UsersTasksService.getUser($scope.user_id, (response) => {
            $scope.user = response.data
        })

        $scope.carregarTasksById = function () {
            $scope.loading = true;
            UsersTasksService.getTasksById($scope.user_id, (response) => {
                $scope.tasks = response
                $scope.loading = false
            })
        }

        $scope.carregarTasksById();

        $scope.saveTask = function() {
            let data = $scope.task;
            data.user_id = $scope.user_id;
            data.data_expiracao = moment(data.data_expiracao).format("YYYY-MM-DD HH:mm:ss");

            $scope.dataLoading = true;
            UsersTasksService.saveTask(data, (response) => {
                $scope.carregarTasksById();
                $scope.task = {};
                $scope.dataLoading = false;
                $("#modalTask").modal("hide");
                alert("Salvo");
            }, (response_err) => {
                alert(response_err?.msg);
                $scope.dataLoading = false;
            })
        }

        $scope.viewTask = function(task) {
            $scope.task = task;
            $scope.task.data_expiracao = new Date(task.data_expiracao),
            $scope.task.view = true;
            $("#modalTask").modal("show");
        }

        $scope.editTask = function(task) {
            $scope.task = task;
            delete task.view;
            $scope.task.data_expiracao = new Date(task.data_expiracao),
            $("#modalTask").modal("show");
        }

        $scope.excluir = function(task_id) {
            if(confirm("Deseja mesmo excluir a tarefa?")) {
                UsersTasksService.excluir(task_id, (response) => {
                    UsersTasksService.getTasksById($scope.user_id, (response) => {
                        $scope.carregarTasksById();
                    })
                    alert("Excluído");
                })
            }
        }

        $scope.moment = moment;


    }]);