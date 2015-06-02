app.controller('MainCtrl',function($scope,$location)
	{
		$scope.menuClass =function(page)
		 {
			 var current =$location.path().substring(1);
			 return page === current ? "active" :"";
		 };
	
});
app.controller('authCtrl', function ($scope, $rootScope, $routeParams, $location, $http, Data,SettingsService) {
    //initially set those objects to null to avoid undefined error
    $scope.login = {};
    $scope.signup = {};
    $scope.doLogin = function (customer) {
        Data.post('login', {
            customer: customer
        }).then(function (results) {
            Data.toast(results);
            if (results.status == "success") {
                $location.path('dashboard');
            }
			else{
				$location.path('login');
			}
        });
    };
    $scope.signup = {names:'',cell:'',email:'',password:''};
    $scope.signUp = function (customer) {
        Data.post('signUp', {
            customer: customer
        }).then(function (results) {
            Data.toast(results);
            if (results.status == "success") {
                $location.path('verification');
            }
        });
    };
	
	
	
	
    $scope.logout = function () {
        Data.get('logout').then(function (results) {
            Data.toast(results);
            $location.path('login');
        });
    }
});
app.controller('associates', function ($scope, $rootScope, $routeParams, $location, $http, Data,SettingsService) {
     $scope.cell=SettingsService.get('cell');
	 $scope.users={};
	 Data.post('associates', {
            customer: $scope.cell
        }).then(function (results) {
            Data.toast(results);
            if (results.status == "success") {
              $scope.users=results.user;
            }
        });
	 
	/* $scope.verification=sponsor_code:'';
	$scope.verification =function (customer) {
        Data.post('sendsms', {
            customer: customer
        }).then(function (results) {
            Data.toast(results);
            if (results.status == "success") {
                //$location.path('dashboard');
				//$rootScope.sms_credits = results.sms_credits;
            }
        });
    };*/
	$scope.login={};
	$scope.verify = function (customer) {
        Data.post('sponsor', {
            customer: customer
        }).then(function (results) {
            Data.toast(results);
           /* if (results.status == "success") {
                $location.path('dashboard');
            }
			else{
				$location.path('login');
			}*/
        });
    };
	
	 /*$scope.login={verifycode:''};
	$scope.verify = function (customer) {
        Data.post('verifycode', {
            customer: customer
        }).then(function (results) {
            Data.toast(results);
            if (results.status == "success") {
                //$location.path('dashboard');
				//$rootScope.sms_credits = results.sms_credits;
            }
        });
    };*/
	
	
 });