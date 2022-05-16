'use strict';

angular.module('Users')

.controller('UsersController',
    ['$scope', 'UsersService',
    function ($scope, UsersService) {

        $scope.carregarUsers = function () {
            $scope.loading = true;
            UsersService.getUsers((response)=>{
                $scope.users = response; 
                $scope.loading = false;
            })
        }

        $scope.carregarUsers();

        $scope.saveUser = function() {
            let data = $scope.user;
            $scope.dataLoading = true;
            UsersService.saveUser(data, (response) => {
                $scope.carregarUsers();
                $scope.user = {};
                $scope.dataLoading = false;
                $("#add").modal("hide");
            }, (response_err) => {
                alert(response_err?.msg);
                $scope.dataLoading = false;
            })
        }

        $scope.editUser = function(user_id) {
            UsersService.getUser(user_id, (response) => {
                $scope.user = response.data;
                $("#add").modal("show");
            })
        }


        $scope.getUser = function(user_id) {
            $scope.user = {};
            UsersService.getUser(user_id, (response) => {
                $scope.user = response.data;
                $scope.user.view = true;
                $("#add").modal("show");
            })
        }

        $scope.excluir = function(user_id) {
            if(confirm("Deseja mesmo excluir o usuario?")) {
                UsersService.excluir(user_id, (response) => {
                    $scope.carregarUsers();
                })
            }
        }

        $scope.genero_array = [
            {
                id: 0,
                nome: "Feminino"
            },
            {
                id: 1,
                nome: "Masculino"
            }
        ]

    }])