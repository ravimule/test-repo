$postModule = angular.module('postModule', ['ui.filters','pagination', 'ngDialog' ]);

$postModule.directive("fileread", [function () { 
     return {
        scope: {
            fileread: "="
        },
        link: function (scope, element, attributes) {
            element.bind("change", function (changeEvent) {
                scope.$apply(function () {
                    scope.fileread = changeEvent.target.files[0]['name'];
                    //attributes.$set('src', 'image_fetched_from_server.png');
                    //console.log(changeEvent.target.files[0]['name']);
                    // or all selected files:
                    // scope.fileread = changeEvent.target.files;
                });
            });
        }
    }
}]);

$postModule.config(['ngDialogProvider', function (ngDialogProvider) {
        ngDialogProvider.setDefaults({
            className: 'ngdialog-theme-default',
        });
    }]);

  
//var base_path = document.getElementById('base_path').value;
var base_path = "http://localhost/demo/angular/insert/";
$postModule.controller('PostController',function($scope, $http, ngDialog, $timeout){
	$scope.post = {};
	$scope.post.users = [];
	$scope.post.autoname = [];
	$scope.post.company = [];
	$scope.tempUser = {};
	$scope.editMode = false;
	$scope.index = '';
	
	$scope.names = ["john", "bill", "charlie", "robert", "alban", "oscar", "marie", "celine", "brad", "drew", "rebecca", "michel", "francis", "jean", "paul", "pierre", "nicolas", "alfred", "gerard", "louis", "albert", "edouard", "benoit", "guillaume", "nicolas", "joseph"];
	
	

	var url = base_path+'ajax.php';

	/* pagination */	
	$scope.currentPage = 0;
    $scope.pageSize = 3;
    $scope.data = [];
    $scope.numberOfPages=function(){
    	if($scope.post.autoname != ""){
    		return Math.ceil($scope.post.autoname.length/$scope.pageSize);
    	}
    	else{
    		return Math.ceil($scope.post.users.length/$scope.pageSize);
    	}
                       
    }
    /* end pagination */

       $scope.openTemplate = function () {
       		$scope.tempUser = {};
            $scope.value = true;
            ngDialog.open({
                template: 'externalTemplate.php',
                className: 'ngdialog-theme-plain',
                scope: $scope
            });
        };

       $scope.editTemplate = function (user) {
       		$scope.editUser(user);
            $scope.value = true;
            ngDialog.open({
                template: 'externalTemplate.php',
                className: 'ngdialog-theme-plain',
                scope: $scope
            });
        };

     
     

    	
	$scope.saveUser = function(){
	    $http({
	      method: 'post',
	      url: url,
	      data: $.param({'user' : $scope.tempUser, 'type' : 'save_user' }),
	      headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	    }).
	    success(function(data, status, headers, config) {
	    	if(data.success){ 
	    		if( $scope.editMode ){ console.log($scope.tempUser);
	    			$scope.post.users[$scope.index].id = data.id;
	    			$scope.post.users[$scope.index].name = $scope.tempUser.name;
	    			$scope.post.users[$scope.index].email = $scope.tempUser.email;
	    			$scope.post.users[$scope.index].companyName = $scope.tempUser.companyName;
	    			$scope.post.users[$scope.index].designation = $scope.tempUser.designation;
	    		 	$scope.post.users[$scope.index].avatar = $scope.tempUser.avatar;
	    		}else{ 
	    			$scope.post.users.push({
		    			id : data.id,
		    			name : $scope.tempUser.name,
		    			email : $scope.tempUser.email,
		    			companyName : $scope.tempUser.companyName,
		    			designation : $scope.tempUser.designation,
		    			avatar : $scope.tempUser.avatar
		    		});
	    		}
	    		$scope.messageSuccess(data.message);
	    		$scope.userForm.$setPristine();
	    		$scope.tempUser = {};
	    		
	    	}else{
	    		$scope.messageFailure(data.message);
	    	}
	    }).
	    error(function(data, status, headers, config) {
	        //$scope.codeStatus = response || "Request failed";
	    });
	    
	    jQuery('.btn-save').button('reset');
	}
	
	$scope.addUser = function(){
		
		jQuery('.btn-save').button('loading');
		$scope.saveUser();
		$scope.editMode = false;
		$scope.index = '';
	}
	
	/*$scope.uploadFile = function(files) {
	   	var file_data = $("#avatar").prop("files")[0];   // Getting the properties of file from file field
		var form_data = new FormData();                  // Creating object of FormData class
		form_data.append("file", file_data);    
	    $.ajax({
		      method: 'post',
		      url: 'upload.php',
		      data: form_data,
		      contentType: false,
			  processData: false,

		    }).
		    success(function(data, status, headers, config) {
		    	if(data){
		    		$("#avatar_pic").val(data);
		    	}else{
		    		alert("not");
		    	}
		    });
	}*/

    // NOW UPLOAD THE FILES.
    $scope.uploadFile = function () {
        var file_data = $("#avatar").prop("files")[0];   // Getting the properties of file from file field
		var form_data = new FormData();                  // Creating object of FormData class
		form_data.append("file", file_data);

		var request = {
	            method: 'POST',
	            url: 'upload.php',
	            data: form_data,
	            headers: {
	                'Content-Type': ''
	        	}
        };

        // SEND THE FILES
        $http(request).success(function (d) {
            console.log('uploaded');
        })
       	.error(function () {
        	console.log('problem in uploading');
        });
        
   	}
	
	$scope.updateUser = function(){
		$('.btn-save').button('loading');
		$scope.saveUser();
	}
	
	$scope.editUser = function(user){
		//var img = $(".edit_avatar").val();
		//$(".edit_avatar_show").attr('src', 'uploads/'+img);
		$scope.tempUser = {
			id: user.id,
			name : user.name,
			email : user.email,
			companyName : user.companyName,
			designation : user.designation,
			avatar : user.avatar,
		};
		$scope.editMode = true;
		$scope.index = $scope.post.users.indexOf(user);
	}
	
	
	$scope.deleteUser = function(user){
		var r = confirm("Are you sure want to delete this user!");
		if (r == true) {
			$http({
		      method: 'post',
		      url: url,
		      data: $.param({ 'id' : user.id, 'type' : 'delete_user' }),
		      headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		    }).
		    success(function(data, status, headers, config) {
		    	if(data.success){
		    		var index = $scope.post.users.indexOf(user);
		    		$scope.post.users.splice(index, 1);
		    	}else{
		    		$scope.messageFailure(data.message);
		    	}
		    }).
		    error(function(data, status, headers, config) {
		    	//$scope.messageFailure(data.message);
		    });
		}
	}
	$scope.searchUser = function(companyName){
		$http({
	      method: 'post',
	      url: url,
	      data: $.param({ 'companyName' : companyName, 'type' : 'search_user' }),
	      headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	    }).
	    success(function(data, status, headers, config) {
	    	if(data.success){
	    		$scope.post.users = data.data;
	    		//$scope.post.tempData = "searched";
	    	}else{
	    		$scope.messageFailure(data.message);
	    	}
	    }).
	    error(function(data, status, headers, config) {
	    	//$scope.messageFailure(data.message);
	    });
	}

	$scope.searchDesignation = function(designation){
		$http({
	      method: 'post',
	      url: url,
	      data: $.param({ 'designation' : designation, 'type' : 'search_des' }),
	      headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	    }).
	    success(function(data, status, headers, config) {
	    	if(data.success){
	    		$scope.post.users = data.data;
	    		//$scope.post.tempData = "searched";
	    	}else{
	    		$scope.messageFailure(data.message);
	    	}
	    }).
	    error(function(data, status, headers, config) {
	    	//$scope.messageFailure(data.message);
	    });
	}

	$scope.autocomplete = function(autocomplete){
		$scope.class = "active";
		$http({
	      method: 'post',
	      url: url,
	      data: $.param({ 'autocomplete' : autocomplete, 'type' : 'autocomplete' }),
	      headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	    }).
	    success(function(data, status, headers, config) {
	    	if(data.success){
	    		$scope.post.autoname = data.data;
	    		console.log($scope.post.autoname);
	    		//$scope.post.tempData = "searched";
	    	}else{
	    		$scope.messageFailure(data.message);
	    	}
	    }).
	    error(function(data, status, headers, config) {
	    	//$scope.messageFailure(data.message);
	    });
	}

	$scope.user_selected = function(user_selected){
		$scope.class = "not-active";
		$http({
	      method: 'post',
	      url: url,
	      data: $.param({ 'user_selected' : user_selected, 'type' : 'user_selected' }),
	      headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	    }).
	    success(function(data, status, headers, config) {
	    	if(data.success){
	    		$scope.post.userSelected = user_selected;
	    		$scope.post.autoname = data.data;
	    		console.log($scope.post.autoname);
	    	}else{
	    		$scope.messageFailure(data.message);
	    	}
	    }).
	    error(function(data, status, headers, config) {
	    	//$scope.messageFailure(data.message);
	    });
	}
	
	
	$scope.initss = function(){
	    $http({
	      method: 'post',
	      url: url,
	      data: $.param({ 'type' : 'getUsers' }),
	      headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	    }).
	    success(function(data, status, headers, config) {
	    	if(data.success && !angular.isUndefined(data.data) ){
	    		$scope.post.users = data.data;
	    		$scope.post.company = data.data;
	    		$scope.post.designation = data.data;
	    	}else{
	    		$scope.messageFailure(data.message);
	    	}
	    }).
	    error(function(data, status, headers, config) {
	    	//$scope.messageFailure(data.message);
	    });
	}
	
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