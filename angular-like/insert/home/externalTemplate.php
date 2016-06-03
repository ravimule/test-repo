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
		<label for="designation">Birth Date</label> 
		<ng-datepicker data-ng-model="tempUser.birthDate" first-week-day-sunday="true" placeholder="Pick a date"></ng-datepicker>
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
