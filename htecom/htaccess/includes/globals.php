<?php
	define('SALT', crypt('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'));
	define('SCRIPT_NAME', basename($_SERVER['SCRIPT_NAME']));
	define('CWD', (($getcwd = getcwd()) ? $getcwd : '.'));
	define('INC_DIR', dirname(__FILE__));
	date_default_timezone_set('Asia/Saigon');
	
	require_once( INC_DIR . '/class_bootstrap.php');
	$bootstrap = new vH_Bootstrap_Site();
	$bootstrap->bootstrap();
	
	
	if( !is_object($vhcore->db) )
	{
		exit('db object don\'t exists.');
	}
?>