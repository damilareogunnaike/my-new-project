<?php include("header_files.php");?>

<div class="row margin-top-xxxl animated fadeIn">
    <div class="col-md-6 col-sm-10 col-xs-10 col-xs-offset-1 col-sm-offset-1 padding-lg bg-white col-md-offset-3">
        <div class="row">
            <div class="bg-white padding-md col-md-5 text-center">
                <?=img(array("src"=>"assets/images/evangel-logo.jpg","class"=>"img margin-auto img-responsive"));?>
            </div>
            <div class="col-md-1 hidden-sm hidden-xs">
                <div class="hr-divider"></div>
            </div>
            <div class="col-md-6 login-form" ng-controller="LoginController">
                <form name="loginForm" action="<?=site_url("login");?>" method="post">
                <h3 class="text-center color2"><i class="fa fa-lock"></i> Login </h3>
                <hr>
                <?=isset($msg) ? $msg : '';?>
                <div class="form-group">
                    <label class="control-label hidden-sm hidden-xs">Username</label>
                    <input type="text" name="username" class="form-control input-sm" ng-model="loginData.username" ng-required="true">
                </div>
                <div class="form-group">
                    <label class="control-label hidden-sm hidden-xs">Password</label>
                    <input type="password" name="password" class="form-control input-sm" ng-model="loginData.password" ng-required="true">
                </div>
                <hr>
                <div class="form-group">
                <label>
                    <input type="checkbox" name="remember_me" ng-model="loginData.remember_me"> Keep me logged in
                </label><br>
                <button ng-disabled="loginForm.$invalid" class="btn btn-primary btn-sm" ng-click="login()"><i class="fa fa-unlock-alt"></i> Login</button>
                <button type="reset" class="btn btn-warning btn-sm"> Reset</button>
                <?=anchor('#forgot_password','<i class="fa fa-question-circle"></i> Forgot Password',array('class'=>'pull-right'))?>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include("footer_files.php");?>