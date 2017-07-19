<!DOCTYPE html>
<html>
<head>
    <title><?=isset($title) ? $title : '';?></title>

    <?=link_tag('assets/bootstrap/css/bootstrap.css')?>
    <?=link_tag('assets/css/style.css')?>
    <?=link_tag('assets/css/print-style.css');?>

    
	<style>
		img {
            width:100px;
            height:100px;
        }

        .no-bg {
        	background: none;
        }

       @media print {
        table tr td {
            padding:0px;
        }
       }
	</style>
</head>

<body class="no-bg">