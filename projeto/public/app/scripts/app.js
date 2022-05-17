'use strict';

// declare modules
angular.module('Authentication', []);
angular.module('Home', []);
angular.module('Task', []);
angular.module('Admin', []);
angular.module('Users', []);
angular.module('UsersTasks', []);

angular.module('BasicHttpAuthExample', [
    'Authentication',
    'Home',
    'Task',
    'Users',
    'Admin',
    'UsersTasks',
    'ngRoute',
    'ngCookies'
])

.config(['$routeProvider', function ($routeProvider) {

    $routeProvider
        .when('/login', {
            controller: 'LoginController',
            templateUrl: 'modules/authentication/views/login.html'
        })

        .when('/', {
            controller: 'HomeController',
            templateUrl: 'modules/home/views/home.html'
        })

        .when('/task', {
            controller: 'TaskController',
            templateUrl: 'modules/task/views/task.html'
        })

        
        //admin rotas
        .when('/admin', {
            controller: 'AdminController',
            templateUrl: 'modules/admin/views/admin.html'
        })
        
        .when('/users', {
            controller: 'UsersController',
            templateUrl: 'modules/users/views/users.html'
        })

        .when('/users/add', {
            controller: 'UsersController',
            templateUrl: 'modules/users/views/add.html'
        })

        .when('/users/tasks/:user_id', {
            controller: 'UsersTasksController',
            templateUrl: 'modules/usersTasks/views/usersTasks.html'
        })

        .otherwise({ redirectTo: '/login' });
}])

.run(['$rootScope', '$location', '$cookieStore', '$http',
    function ($rootScope, $location, $cookieStore, $http) {
        // keep user logged in after page refresh
        $rootScope.globals = $cookieStore.get('globals') || {};
        if ($rootScope.globals.currentUser) {
            $http.defaults.headers.common['Authorization'] = 'Basic ' + $rootScope.globals.currentUser.authdata; // jshint ignore:line
        }

        $rootScope.$on('$locationChangeStart', function (event, next, current) {
            // redirect to login page if not logged in
            if ($location.path() !== '/login' && !$rootScope.globals.currentUser) {
                $location.path('/login');
            }
        });
    }]);
