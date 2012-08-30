<?php 
	define('DS', DIRECTORY_SEPARATOR);	
	
	include_once('includes' . DS . 'globals.php');
	include_once('includes' . DS . 'incFontEnd.php');	
	
	
	$vhcore->st->assign(array(
		'arrLink' => array('control'=>$_control, 'view'=>$_view),
		'tplView' => $tplView
	));
	$vhcore->st->display('index.tpl');
?>