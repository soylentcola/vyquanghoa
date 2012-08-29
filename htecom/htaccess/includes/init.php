<?php
	require_once(INC_DIR . '/class_core.php');
	
	$vhcore = new vH_Registry();
	$vhcore->fetch_config();
	
	// load database class #############################################################################
	require_once( INC_DIR . '/class_mysqlv.php');
	$db = new Mysql_DB($vhcore);
	$db->connect();
	$vhcore->db =& $db;
		
	// load upload class #############################################################################
	require_once( INC_DIR . '/class_file_upload.php');
	$FileUpload = new FileUpload();
	$vhcore->FileUpload = $FileUpload;
	
	// load thumb class #############################################################################
	require_once( INC_DIR . '/phpthumb/ThumbLib.inc.php');
	