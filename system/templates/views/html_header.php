<!doctype html>
<html>
<head>
<title><?=APPLICATION_NAME?></title>
<?php if (file_exists($f = BASE_URL.'frontend/css/layout.css')) { ?>
<link rel="stylesheet" href="<?=f?>"></link>
<?php } ?>
<?php if (file_exists($f = BASE_URL.'frontend/css/elements.css')) { ?>
<link rel="stylesheet" href="<?=f?>"></link>
<?php } ?>
<!--[if IE]>
<script type="text/javascript" src="<?=BASE_URL?>frontend/js/html5.js"></script>
<![endif]-->
<script type="text/javascript" src="<?=BASE_URL?>frontend/js/jquery.js"></script>
</head>
<body>