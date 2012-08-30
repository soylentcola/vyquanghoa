<?php
	require_once(INC_DIR . DS . 'class_core.php');
	
	$vhcore = new vH_Registry();
	$vhcore->fetch_config();
	
	// load database class #############################################################################
	require_once( INC_DIR . DS . 'class_mysqlv.php');
	$db = new Mysql_DB($vhcore);
	$db->connect();
	$vhcore->db =& $db;
		
	// load upload class #############################################################################
	require_once( INC_DIR . DS . 'class_file_upload.php');
	$FileUpload = new FileUpload();
	$vhcore->FileUpload = $FileUpload;
	
	// load thumb class #############################################################################
	require_once( INC_DIR . DS . 'phpthumb'.DS.'ThumbLib.inc.php');
	
	// load controller class #############################################################################
	//require_once( INC_DIR . DS . 'class.controller.php');
	