var app = angular.module('myApp', ['ngRoute', 'ngAnimate', 'toaster']);

app.config(['$routeProvider',
  function ($routeProvider) {
        $routeProvider.
        when('/login', {
            title: 'Login',
            templateUrl: 'pages/login.html',
            controller: 'authCtrl'
        })
            .when('/logout', {
                title: 'Logout',
                templateUrl: 'pages/main.html',
                controller: 'logoutCtrl'
            })
			.when('/mainpage', {
                title: 'MyEmail-Business',
                templateUrl: 'pages/main.html',
                controller: 'logoutCtrl'
            })
			.when('/strategy', {
                title: 'strategy plan',
                templateUrl: 'pages/strategy.html',
                controller: ''
            })
			.when('/planning', {
                title: 'strategy plan',
                templateUrl: 'pages/strategy.html',
                controller: 'logoutCtrl'
            })
			.when('/marketing_plan', {
                title: 'strategy plan',
                templateUrl: 'pages/strategy.html',
                controller: 'logoutCtrl'
            })
            .when('/signup', {
                title: 'Signup',
                templateUrl: 'pages/signup.html',
                controller: 'authCtrl'
            })
            .when('/dashboard', {
                title: 'Dashboard',
                templateUrl: 'pages/home.html',
                controller: 'associates'
            })
			  .when('/verification', {
                title: 'Dashboard',
                templateUrl: 'pages/verification.html',
                controller: 'associates'
            })
            .when('/', {
                title: 'main page',
                templateUrl: 'pages/main.html',
                controller: 'authCtrl',
                role: '0'
            })
            .otherwise({
                redirectTo: '/mainpage'
            });
  }])
    .run(function ($rootScope, $location, Data) {
        $rootScope.$on("$routeChangeStart", function (event, next, current) {
            $rootScope.authenticated = false;
            Data.get('session').then(function (results) {
                if (results.s_code) {
                    $rootScope.authenticated = true;
                    $rootScope.bank_d = results.bank_d;
                    $rootScope.names = results.names;
					$rootScope.cell =results.cell;
					
					//$rootScope.num_learners = results.num_learners;
                } /*else {
                    var nextUrl = next.$$route.originalPath;
                    if (nextUrl == '/signup' || nextUrl == '/login') {

                    } else {
                        $location.path("/signup");
                    }
                }*/
            });
        });
    });