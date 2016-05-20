$RegModule = angular.module('regModule', []);

//var base_path = document.getElementById('base_path').value;
var base_path = "http://localhost/demo/angular/insert/";
$RegModule.controller('RegController',function($scope, $http){
	$scope.post = {};
	$scope.user = {};
    // calling our submit function.
    $scope.submitForm = function(user) {
    	// Posting data to php file
        $http({
          method  : 'POST',
          url     : 'submit_action.php',
          data	  : $.param({'user' : $scope.user, 'type' : 'reg_user' }),
          headers : {'Content-Type': 'application/x-www-form-urlencoded'} 
         })
          .success(function(data) {
            if (data.errors) {
              // Showing errors.
              $scope.errorName = data.errors.name;
              $scope.errorUserName = data.errors.username;
              $scope.errorEmail = data.errors.email;
              $scope.messageFailure(data.message);
            } else {
              $scope.message = data.message;
              $scope.messageSuccess(data.message);
            }
        });
    };

    $scope.loginFormSubmit = function(user) {
    	// Posting data to php file
        $http({
          method  : 'POST',
          url     : 'submit_action.php',
          data	  : $.param({'user' : $scope.user, 'type' : 'login_user' }),
          headers : {'Content-Type': 'application/x-www-form-urlencoded'} 
         })
          .success(function(data) { console.log(data.success);
            if (data.success) {
              	window.location.href = 'home/home.php';
            } else {
              	$scope.message = data.message;
				$scope.messageFailure(data.message);
            }
        });
    }; 

	$scope.messageFailure = function (msg){
		jQuery('.alert-failure-div > p').html(msg);
		jQuery('.alert-failure-div').show();
		jQuery('.alert-failure-div').delay(5000).slideUp(function(){
			jQuery('.alert-failure-div > p').html('');
		});
	}
	
	$scope.messageSuccess = function (msg){
		jQuery('.alert-success-div > p').html(msg);
		jQuery('.alert-success-div').show();
		jQuery('.alert-success-div').delay(5000).slideUp(function(){
			jQuery('.alert-success-div > p').html('');
		});
	}
	
	
	$scope.getError = function(error, name){
		if(angular.isDefined(error)){
			if(error.required && name == 'name'){
				return "Please enter name";
			}else if(error.email && name == 'email'){
				return "Please enter valid email";
			}else if(error.required && name == 'company_name'){
				return "Please enter company name";
			}else if(error.required && name == 'designation'){
				return "Please enter designation";
			}else if(error.required && name == 'email'){
				return "Please enter email";
			}else if(error.minlength && name == 'name'){
				return "Name must be 3 characters long";
			}else if(error.minlength && name == 'company_name'){
				return "Company name must be 3 characters long";
			}else if(error.minlength && name == 'designation'){
				return "Designation must be 3 characters long";
			}
		}
	}
	
});