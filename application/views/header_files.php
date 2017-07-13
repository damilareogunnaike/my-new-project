<!DOCTYPE html>
<html ng-app="myApp">
<head>
	<title><?=isset($title) ? $title : '';?></title>
	<?=link_tag('assets/bootstrap/css/metro-bootstrap.css')?>
    <?=link_tag('assets/node_modules/sweetalert/dist/sweetalert.css')?>
	<?=link_tag('assets/fonts/font-awesome/css/font-awesome.css')?>
	<?=link_tag('assets/css/animations.css')?>
	<?=link_tag('assets/css/animate.css')?>
	<?=link_tag('assets/css/jquery.dataTables.css')?>
	<?=link_tag('assets/css/angular-csp.css')?>
	<?=link_tag('assets/css/style.css')?>

	<script>
		var APP_BASE_URL = "<?=$this->config->item('base_url');?>";
	</script>
</head>
