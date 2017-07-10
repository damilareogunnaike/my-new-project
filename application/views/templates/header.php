<html ng-app="myApp" ng-cloak>
<head>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,400italic">
    <?=link_tag('assets/node_modules/angular-material/angular-material.min.css')?>
    <?=link_tag('assets/node_modules/angular-material-data-table/dist/md-data-table.css')?>
    <?=link_tag('assets/css2/style.css')?>

    <script>
        var APP_BASE_URL = "<?=$this->config->item('base_url');?>";
    </script>
    <title>Portal</title>
</head>
<body>