<?php
	//=> use with assocative array
	//-> use to access method & property from object
	//::

	// error reportin on
	ini_set('display_errors', 'on');
	error_reporting(E_ALL); 

	$sessionUser = '';
	if(isset($_SESSION['user'])) {
		$sessionUser = $_SESSION['user'];
	}

	include  'admin/connect.php';

	// Routes

	$tpl 	= 'includes/templates/'; // Template Directory
	$lang 	= 'includes/languages/'; // Language Directory
	$func	= 'includes/functions/'; // Functions Directory
	$css 	= 'layout/css/'; // Css Directory
	$js 	= 'layout/js/'; // Js Directory

	// Include The Important Files
    include $func . 'functions.php';
	include $lang . 'english.php';
    include $tpl . 'header.php';
