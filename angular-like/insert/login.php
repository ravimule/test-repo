<?php
session_start();
if(isset($_SESSION['user_session']))
{
    header("Location: home/home.php");
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap 101 Template</title>
    <link href="home/css/bootstrap.min.css" rel="stylesheet" />
    <link href="home/css/font-awesome.min.css" rel="stylesheet">
    <link href="home/css/animate.min.css" rel="stylesheet">
    <link href="home/css/style.css" rel="stylesheet">

    <script src="home/js/jquery.min.js"></script>
    <script src="home/js/bootstrap.min.js"></script>
    <script src="home/js/angular.min.js"></script>
    <script src="js/angular-register.js"></script>
  </head>

  <body class="ng-scope" data-ng-controller="RegController" ng-app="regModule">
    <div class="container">
      <div class="col-sm-8">
        <div class="alert alert-danger text-center alert-failure-div" role="alert" style="display: none">
          <p></p>
        </div>
        <div class="alert alert-success text-center alert-success-div" role="alert" style="display: none">
          <p></p>
        </div>
        <form novalidate name="loginForm" class="form-horizontal ng-valid-required ng-dirty ng-invalid ng-invalid-email ng-valid-minlength" ng-submit="loginFormSubmit(user)">
          <div class="form-group">
            <label for="email" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-4">
              <input type="email" name="email" id="email" class="form-control ng-touched ng-valid-required ng-invalid ng-invalid-email ng-valid-minlength" ng-model="user.email" required="">
            </div>
          </div>
          <div class="form-group">
            <label for="password" class="col-sm-2 control-label">Password</label>
            <div class="col-sm-4">
              <input type="password" class="form-control ng-touched ng-valid-minlength ng-pristine ng-valid ng-valid-required" id="password" name="password" ng-model="user.pass" required="">
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <button class="btn btn-success" type="submit" data-loading-text="Saving User..." ng-disabled="loginForm.$invalid" disabled="disabled">Login</button>
              <a href="register.php" class="btn btn-success">Register</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>