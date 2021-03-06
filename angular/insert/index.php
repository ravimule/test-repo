<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>AngularJS Insert Update Delete Using PHP MySQL</title>
	<!-- Bootstrap -->
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,200' rel='stylesheet' type='text/css'>	
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/animate.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">

	<!-- Script -->
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="js/jquery.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="js/bootstrap.min.js"></script>
	<script src="js/angular.min.js"></script>
	<script src="js/angular-custom.js"></script>
	<script src="js/unique.js"></script>
	<script src="js/pagination.js"></script>
</head>
<body class="ng-scope" data-ng-init="initss()" data-ng-controller="PostController" data-ng-app="postModule">
<input type="hidden" value="http://localhost/demo/angular/insert/" id="base_path">
<div class="container">
	<h2 class="title text-center">AngularJS Insert Update Delete Using PHP MySQL</h2>

	<div class="row mt80">
		<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 animated fadeInDown">
			<div class="alert alert-danger text-center alert-failure-div" role="alert" style="display: none">
				<p></p>
			</div>
			<div class="alert alert-success text-center alert-success-div" role="alert" style="display: none">
				<p></p>
			</div>
			<form novalidate name="userForm" class="ng-valid-required ng-dirty ng-invalid ng-invalid-email ng-valid-minlength" enctype="multipart/form-data">
				<div class="form-group">
					<label for="name">Name</label> 
					<input type="text" data-ng-model="tempUser.name" placeholder="Name" name="name" id="name" class="form-control ng-touched ng-valid-minlength ng-pristine ng-valid ng-valid-required" required="" data-ng-minlength="3">
				</div>
				<div class="form-group">
					<label for="email">Email</label> 
					<input type="email" data-ng-model="tempUser.email" placeholder="Email" name="email" id="email" class="form-control ng-touched ng-valid-required ng-invalid ng-invalid-email ng-valid-minlength" required="" data-ng-minlength="3">
				</div>
				<div class="form-group">
					<label for="company_name">Company Name</label> 
					<input type="text" data-ng-model="tempUser.companyName" placeholder="Company Name" name="company_name" id="company_name" class="form-control ng-valid-minlength ng-touched ng-pristine ng-valid ng-valid-required" required="" data-ng-minlength="3">
				</div>
				<div class="form-group">
					<label for="designation">Designation</label> 
					<input type="text" data-ng-model="tempUser.designation" placeholder="Designation" name="designation" id="designation" class="form-control ng-valid-minlength ng-touched ng-pristine ng-valid ng-valid-required" required="" data-ng-minlength="3">
				</div>
				<div class="form-group">
					<label for="avatar">Avatar</label> 
					<input type="file" name="avatar" ng-model="tempUser.avatar"  id="avatar" fileread="tempUser.avatar"  onchange="angular.element(this).scope().uploadFile(this.files)" >
					<!-- <img src=""  class="edit_avatar_show"/> -->
					<!-- <input type="text" data-ng-model="tempUser.avatar" class="edit_avatar" />  -->
					<input type="hidden" name="avatar_pic" id="avatar_pic" />
				</div>
				    
				<div class="text-center">
					<button data-ng-click="addUser()" class="btn btn-save" type="submit" ng-hide="tempUser.id" data-loading-text="Saving User..." ng-disabled="userForm.$invalid" disabled="disabled">Save User</button>
					<button data-ng-click="updateUser()" class="btn btn-save" type="submit" ng-hide="!tempUser.id" data-loading-text="Updating User..." ng-disabled="userForm.$invalid" disabled="disabled">Update User</button>
				</div>
			</form>
		</div>
		<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 animated fadeInUp">
			<div class="form-group">
				<label for="companyName">Search</label> 
				<select ng-model="companyName" ng-selected="companyName" ng-change="searchUser(companyName)">
					<option class="ng-scope" value="">Select</option>
					<option class="ng-scope" data-ng-repeat="user in post.company | unique:'companyName'" >{{user.companyName}}</option>
				</select>
			</div>
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th width="5%">#</th>
							<th width="10%">Profile</th>
							<th width="15%">Name</th>
							<th width="20%">Email</th>
							<th width="15%">Company Name</th>
							<th width="15%">Designation</th>
							<th width="30%">Action</th>
						</tr>
					</thead>
					<tbody>
						<tr class="ng-scope" data-ng-repeat="user in post.users | orderBy : '-id' | startFrom:currentPage*pageSize | limitTo:pageSize" >
							<th class="ng-binding" scope="row">{{user.id}}</th>
							<td class="ng-binding"><img ng-src="uploads/{{user.avatar}}" style="width:60px"/></td>
							<td class="ng-binding">{{user.name}}</td>
							<td class="ng-binding">{{user.email}}</td>
							<td class="ng-binding">{{user.companyName}}</td>
							<td class="ng-binding">{{user.designation}}</td>
							<td><span class="links" data-ng-click="editUser(user)"> Edit</span> | <span data-ng-click="deleteUser(user)" class="links">Delete</span></td>
						</tr>
					</tbody>
				</table>
				<button ng-disabled="currentPage == 0" ng-click="currentPage=currentPage-1">
			        Previous
			    </button>
			    {{currentPage+1}}/{{numberOfPages()}}
			    <button ng-disabled="currentPage >= numberOfPages() - 1" ng-click="currentPage=currentPage+1">
			        Next
			    </button>
			</div>
		</div>
	</div>
</div>

</body>
</html>
