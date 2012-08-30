<?php	
	//$vhcore->userinfo = unserialize($_SESSION[sessName('userinfo')]);
	
	//print_r($vhcore);
	//##########################################################################
	//load pager class
	/*include_once ( INC_DIR . '/class_pager.php');
	$vhcore->pager = new multilPages;*/
	
	//##########################################################################
	//load smarty templates class
	include_once ( INC_DIR . DS . '/class_smarty.php');
	$vhcore->st = new Smarty;
	
	//load cookie class
	/*include_once ( INC_DIR . '/class_cookie.php');
	$vhcore->cookie = new Cookie;*/
	
	//##########################################################################
	
	
	include(INC_DIR . DS. 'class_context.php');
	
	if(SCRIPT_NAME == 'ajax.php')
		return;
	
	$vhcore->input->clean_array_gpc('r', array(
		'control'  => TYPE_STR,
		'view'  => TYPE_STR,
		'req_1'  => TYPE_STR,
		'req_2'  => TYPE_STR,
		'req_3'  => TYPE_STR
	));
	$_control = ucfirst($vhcore->GPC['control']);
	$_view = ucfirst($vhcore->GPC['view']);
	
	if(empty($_control)){
		$_control = 'Main';
		$_view = 'Index';		
	}else if(empty($_view)){
		$_view = 'Index';
	}
	$control_replace = array(
		'tin-tuc' => 'news'
	);
	$control_replace_flip = array_flip($control_replace);	
	
	if( array_key_exists($_control, $control_replace) )
		$_control = $control_replace[$_control];
	else if(array_key_exists($_control, $control_replace_flip) || ucfirst($vhcore->GPC['control']) == 'Main')
		redir($vhcore->options['sitepath']);	
		

		
	//print_r($vhcore->GPC);
	$tplView  = tplView($_control, $_view);
	
	includeController($_control);	
	