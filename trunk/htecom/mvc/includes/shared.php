<?php
	function includeController($control)
	{
		if(empty($control))
			$control = 'Main';
		$control = ucfirst($control);
		if(!includeControl($control))
			includeControl('Main');
	}
	
	function includeControl($control)
	{
		global $vhcore;
		$controller_file = CWD . DS . 'controller' . DS . $control. DS . $control . 'Controller.php';
		$model_file = CWD . DS . 'model' . DS . $control. DS . $control . 'Model.php';
		
		if(file_exists($controller_file)){
			include_once($model_file);
			include_once($controller_file);
			$controller_ = $control.'Controller';
			$controller = new $controller_;
			call_user_func(array($controller, 'run')); 
			return true;
		}
		return false;
		//redir($vhcore->options['sitepath']);
	}
	
	function tplView($control, $view){
		return ucfirst($control) . DS . ucfirst($control) . '.' . ucfirst($view) . '.tpl';
	}
	
	function setReporting()
	{
		if (DEVELOPMENT_ENVIRONMENT)
		{
			error_reporting(E_ALL);
			ini_set('display_errors','On');
		} else {
			error_reporting(E_ALL);
			ini_set('display_errors','Off');
			ini_set('log_errors', 'On');
			ini_set('error_log', CWD.DS.'tmp'.DS.'logs'.DS.'error.log');
		}
	}
	setReporting();