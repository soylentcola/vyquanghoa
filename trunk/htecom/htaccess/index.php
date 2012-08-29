<?php
	include('includes/globals.php');
	echo SCRIPT_NAME;
	
	//$vhcore->db->assign('a','b');
	//$vhcore->db->assign('c','d');
	//$vhcore->db->fields = array('a'=>'v','c'=>5);
	echo $vhcore->db->update('table','a=1');
	//print_r($_REQUEST);
?>