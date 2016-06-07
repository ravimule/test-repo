<?php
session_start();
if(!isset($_SESSION['user_session']))
{
    header("Location: ../login.php");
    exit;
}
echo $_SESSION['user_session'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>AngularJS Insert Update Delete Using PHP MySQL</title>
	<!-- Bootstrap -->
	<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,200' rel='stylesheet' type='text/css'>	
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/animate.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<link href="css/autocomplete.css" rel="stylesheet">
	<link rel="stylesheet" href="css/ngDialog.css">
	<link rel="stylesheet" href="css/ngDialog-theme-default.css">
    <link rel="stylesheet" href="css/ngDialog-theme-plain.css">
    <link rel="stylesheet" href="css/ngDialog-custom-width.css">
	<link rel="stylesheet" type="text/css" href="css/ngDatepicker.css">

	<!-- Script -->
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="js/jquery.min.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script src="js/bootstrap.min.js"></script>
	<script src="js/angular.min.js"></script>
	<script src="js/angular-custom.js"></script>
	<script src="js/unique.js"></script>
	<script src="js/pagination.js"></script>
	<script src="js/ngDialog.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment-with-locales.min.js"></script>
	<script src="js/ngDatepicker.js"></script>


</head>
<body class="ng-scope" data-ng-init="initss()" data-ng-controller="PostController" ng-app="postModule">
<input type="hidden" value="http://localhost/demo/angular/insert/" id="base_path">
<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 animated fadeInDown level1">
			<a href="../logout.php">Logout</a>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 animated fadeInDown">
			<h2 class="title text-center">AngularJS Basic operations Using PHP MySQL</h2>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 animated fadeInDown level1">
			<a href="" ng-click="openTemplate()">Add new User</a>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 animated fadeInUp level2">
			<div class="form-group">
				<div class="col-sm-2 col-md-2 col-lg-2">
					<label for="companyName">Company:</label> 
					<select ng-model="companyName" ng-selected="companyName" ng-change="searchUser(companyName)">
						<option class="ng-scope" value="">Select</option>
						<option class="ng-scope" data-ng-repeat="user in post.company | unique:'companyName'" >{{user.companyName}}</option>
					</select>
				</div>
				<div class="col-sm-2 col-md-2 col-lg-2">
					<label for="designation">Designation:</label> 
					<select ng-model="designation" ng-selected="designation" ng-change="searchDesignation(designation)">
						<option class="ng-scope" value="">Select</option>
						<option class="ng-scope" data-ng-repeat="user in post.company | unique:'designation'" >{{user.designation}}</option>
					</select>
				</div>
				<div class="col-sm-3 col-md-3 col-lg-3">
					<label>Search: </label>
					<input ng-model="post.userSelected" ng-change="autocomplete(post.userSelected)" placeholder="Emp Name">
					<div class="autocomplete">
						<ul class="ng-scope autocomplete_result" ng-class="class">
							<li data-ng-click="user_selected(autoname.name)" data-ng-repeat="autoname in post.autoname" >{{autoname.name}}</li>
						</ul>
			      		<!-- selected = {{selected}} -->
			      	</div>
				</div>
				<div class="col-sm-2 col-md-2 col-lg-2">
					<label>Sort: </label>
					<select ng-model="post.sortUser" ng-selected="post.sortUser" ng-change="sortUser(post.sortUser)">
						<option class="ng-scope" value="">Select</option>
						<option class="ng-scope" value="name">name</option>
						<option class="ng-scope" value="email">email</option>
						<option class="ng-scope" value="companyName">Company Name</option>
						<option class="ng-scope" value="designation">Designation</option>
					</select>
				</div>
				<div class="col-sm-2 col-md-1 col-lg-1">
					<label>Order: </label>
					<select class="sortAscDesc" ng-model="post.sort" ng-init="post.sort='a-z'" ng-change="sortAscDesc(post.sort)">
						<option value="a-z">A-Z</option>
						<option value="z-a">Z-A</option>
					</select>
				</div>
			</div>
		</div>

		<script type="text/ng-template" id="largeimage">
	  		<img ng-src="uploads/{{image.avatar}}" style="width:100%" />
	  	</script>
		<!-- <label>Search: <input ng-model="searchText"></label> -->
		<!-- <label>Search: <input ng-model="search.name"></label> -->
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 animated fadeInUp level3">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th width="5%">#</th>
							<th width="10%">Profile</th>
							<th width="10%">Like </th>
							<th width="10%">Name</th>
							<th width="20%">Email</th>
							<th width="15%">Company Name</th>
							<th width="15%">Designation</th>
							<th width="15%">Birth Date</th>
							<th width="30%">Action</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-if="post.sortAscDesc === 'z-a'" ng-hide="post.userSelected" class="ng-scope" data-ng-repeat="user in post.users | orderBy : post.sortUser | reverse | startFrom:currentPage*pageSize | limitTo:pageSize | filter:search:strict" >
							<th class="ng-binding" scope="row">{{user.emp_id}}</th>
							<td class="ng-binding">
								<img ng-src="uploads/{{user.avatar}}" data-ng-click="imageTemplate(user)" style="width:60px" />
							</td>
							<td class="ng-binding">
								<a ng-click="doVote(user.emp_id)" title="If you like this, vote it up!" class="text-center" href="">
							        <i ng-class="( post.rowid == user.emp_id ) ? 'fa-heart' : 'fa-heart-o'" class="fa fa-2x fa-heart"></i><br>
							        <span ng-bind="ply.votes" class="slide-up ng-binding">1</span>
							    </a>
							</td>
							<td class="ng-binding">{{user.name}}</td>
							<td class="ng-binding">{{user.email}}</td>
							<td class="ng-binding">{{user.companyName}}</td>
							<td class="ng-binding">{{user.designation}}</td>
							<td class="ng-binding">{{user.dob | date:'EEE, dd MMM yyyy'}}</td>
							<td><span class="links" data-ng-click="editTemplate(user)"> Edit</span> | <span data-ng-click="deleteUser(user)" class="links">Delete</span></td>
						</tr>
						<tr ng-hide="post.userSelected || post.sortAscDesc === 'z-a'" class="ng-scope" data-ng-repeat="user in post.users | unique:'emp_id' | orderBy : post.sortUser | startFrom:currentPage*pageSize | limitTo:pageSize | filter:search:strict" >
							<th class="ng-binding" scope="row">{{user.emp_id}}</th>
							<td class="ng-binding">
								<img ng-src="uploads/{{user.avatar}}" data-ng-click="imageTemplate(user)" style="width:60px" />
							</td>
							<td class="ng-binding">
								<a ng-click="doVote(user.emp_id)" title="If you like this, vote it up!" class="text-center" href="">
									<i ng-class="( user.flag == 1 ) ? 'fa-heart' : 'fa-heart-o'" class="fa fa-2x fa-heart"></i><br>
									<span class="slide-up ng-binding">Likes: {{user.likes}}</span>
							    </a>
							</td>
							<td class="ng-binding">{{user.name}}</td>
							<td class="ng-binding">{{user.email}}</td>
							<td class="ng-binding">{{user.companyName}}</td>
							<td class="ng-binding">{{user.designation}}</td>
							<td class="ng-binding">{{user.dob | date:'EEE, dd MMM yyyy'}}</td>
							<td><span class="links" data-ng-click="editTemplate(user)"> Edit</span> | <span data-ng-click="deleteUser(user)" class="links">Delete</span></td>
						</tr>
						<tr class="ng-scope" data-ng-repeat="autoname in post.autoname | orderBy : '-id' | startFrom:currentPage*pageSize | limitTo:pageSize | filter:search:strict" >
							<th class="ng-binding" scope="row">{{autoname.emp_id}}</th>
							<td class="ng-binding">
								<img ng-src="uploads/{{autoname.avatar}}" data-ng-click="imageTemplate(autoname)" style="width:60px"/>
							</td>
							<td class="ng-binding">
								<a ng-click="doVote(autoname.emp_id)" title="If you like this, vote it up!" class="text-center" href="">
							        <i ng-class="( post.rowid == user.emp_id ) ? 'fa-heart' : 'fa-heart-o'" class="fa fa-2x fa-heart"></i><br>
							        <span ng-bind="ply.votes" class="slide-up ng-binding">1</span>
							    </a>
							</td>
							<td class="ng-binding">{{autoname.name}}</td>
							<td class="ng-binding">{{autoname.email}}</td>
							<td class="ng-binding">{{autoname.companyName}}</td>
							<td class="ng-binding">{{autoname.designation}}</td>
							<td class="ng-binding">{{autoname.dob | date:'dd MMM yyyy'}}</td>
							<td><span class="links" data-ng-click="editTemplate(autoname)"> Edit</span> | <span data-ng-click="deleteUser(autoname)" class="links">Delete</span></td>
						</tr>
					</tbody>
				</table>
			</div>
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
</body>
</html>
