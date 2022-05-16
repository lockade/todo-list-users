'use strict';

angular.module('Task')

.controller('TaskController',
    ['$scope', 'TaskService',
    function ($scope, TaskService) {

        $scope.carregarTasks = function() {
            $scope.loading = true;
            TaskService.getTask((response)=>{
                $scope.tasks = response;  
                $scope.loading = false;          
            })
        }

        $scope.carregarTasks();

        $scope.concluir = function (task) {
            if(confirm("Deseja mesmo concluir a tarefa ?")) {
                $scope.loading = true;
                TaskService.concluirTask(task.id, () => {
                    $scope.carregarTasks();
                    alert("tarefa concluida");      
                })
            }
        }

        $scope.is_admin = TaskService.checkIsAdmin();

        $scope.moment = moment;
    }]);