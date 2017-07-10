
<!-- Angular JS Files -->
<script src="<?=base_url("assets/js/angular/angular.js");?>"></script>
<script src="<?=base_url("assets/js/angular/angular-route.js");?>"></script>
<script src="<?=base_url("assets/js/angular/angular-animate.min.js");?>"></script>
<script src="<?=base_url("assets/js/angular/angular-cookies.min.js");?>"></script>
<script src="<?=base_url("assets/js/angular/angular-ui-router.js");?>"></script>
<script src="<?=base_url("assets/js/angular/angular-aria.min.js");?>"></script>
<script src="<?=base_url("assets/node_modules/angular-material/angular-material.min.js");?>"></script>
<script src="<?=base_url("assets/node_modules/angular-material-data-table/dist/md-data-table.js");?>"></script>
<script src="<?=base_url("assets/js/angular/angular-sanitize.min.js");?>"></script>
<script src="<?=base_url("assets/js/angular/angular-resource.min.js");?>"></script>
<script src="<?=base_url("assets/js/angular/ui-bootstrap-1.3.1.min.js");?>"></script>
<script src="<?=base_url("assets/js/angular/angular-lahray.js");?>"></script>


<script src="<?=base_url("assets/js/angular/app/angular-app.js");?>"></script>
<script src="<?=base_url("assets/js/angular/app/angular-config.js");?>"></script>
<script src="<?=base_url("assets/js/angular/app/angular-services.js");?>"></script>
<script src="<?=base_url("assets/js/angular/app/angular-directives.js");?>"></script>
<script src="<?=base_url("assets/js/angular/app/angular-filters.js");?>"></script>
<script src="<?=base_url("assets/js/angular/app/angular-controllers.js");?>"></script>
<script src="<?=base_url("assets/js/angular/app/modules/messages-module.js");?>"></script>
<script src="<?=base_url("assets/js/angular/app/modules/events-module.js");?>"></script>
<script src="<?=base_url("assets/js/angular/app/modules/reports-module.js");?>"></script>
<script src="<?=base_url("assets/js/angular/app/modules/sms-module.js");?>"></script>
<script src="<?=base_url("assets/js/angular/app/modules/students-module.js");?>"></script>
<script src="<?=base_url("assets/js/angular/app/modules/school-setup-module.js");?>"></script>
<script src="<?=base_url("assets/js/angular/app/modules/results-module.js");?>"></script>
<script src="<?=base_url("assets/js/angular/app/modules/result-pins-module.js");?>"></script>
<script src="<?=base_url("assets/js/angular/app/modules/parents-module.js");?>"></script>
<?php 
	if(isset($jsModules)){
		foreach($jsModules as $module){
				echo '<script src="' . base_url("assets/js/" . $module . "-module.js") . '"></script>';
		}
	}
?>

