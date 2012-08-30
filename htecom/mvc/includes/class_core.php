<?php
class vH_Registry
{
	var $options = array();
	
	var $db;
	
	var $st;
	
	var $config;	
	
	var $input;
	
	var $GPC = array();
	
	var $GPC_exists = array();
	
	var $lang = 'vn';
	
	var $context;
	
	var $FileUpload;
	
	var $pager;
	
	
	
	function vH_Registry()
	{
		$this->input = new vH_Input_Cleaner($this);
	}
	
	function fetch_config()
	{
		$config = array();
		
		include( INC_DIR . DS . 'config.php');
		
		if (sizeof($config) == 0)
		{
			if (file_exists( INC_DIR. DS . 'config.php'))
			{
				// config.php exists, but does not define $config
				die('<br /><strong>Cau hinh</strong>: <u>includes/config.php</u> khong dung cau hinh.');
			}
			else
			{
				die('<br /><strong>Cau hinh</strong>: <u>includes/config.php</u> khong ton tai');
			}
		}
		$this->config =& $config;
		$this->options['sitepath'] = 'http://'.$_SERVER['HTTP_HOST'].$config['Misc']['sitepath'];
		$this->options['admindir'] =& $config['Misc']['admindir'];
		$this->options['time'] = array(
			'time' => time(),
			'datetime' => date('Y-m-d h:i:s')
		);
		/*if (isset($this->config["$_SERVER[HTTP_HOST]"]))
		{
			$this->config['MasterServer'] = $this->config["$_SERVER[HTTP_HOST]"];
		}*/
		define('TABLE_PREFIX', trim($this->config['Database']['tableprefix']));
		define('COOKIE_PREFIX', (empty($this->config['Misc']['cookieprefix']) ? 'vh' : $this->config['Misc']['cookieprefix']) . '_');
	}
}

define('TYPE_NOCLEAN',      0); // no change

define('TYPE_BOOL',     1); // force boolean
define('TYPE_INT',      2); // force integer
define('TYPE_UINT',     3); // force unsigned integer
define('TYPE_NUM',      4); // force number
define('TYPE_UNUM',     5); // force unsigned number
define('TYPE_UNIXTIME', 6); // force unix datestamp (unsigned integer)
define('TYPE_STR',      7); // force trimmed string
define('TYPE_NOTRIM',   8); // force string - no trim
define('TYPE_NOHTML',   9); // force trimmed string with HTML made safe
define('TYPE_ARRAY',   10); // force array
define('TYPE_FILE',    11); // force file
define('TYPE_BINARY',  12); // force binary string
define('TYPE_NOHTMLCOND', 13); // force trimmed string with HTML made safe if determined to be unsafe

define('TYPE_ARRAY_BOOL',     101);
define('TYPE_ARRAY_INT',      102);
define('TYPE_ARRAY_UINT',     103);
define('TYPE_ARRAY_NUM',      104);
define('TYPE_ARRAY_UNUM',     105);
define('TYPE_ARRAY_UNIXTIME', 106);
define('TYPE_ARRAY_STR',      107);
define('TYPE_ARRAY_NOTRIM',   108);
define('TYPE_ARRAY_NOHTML',   109);
define('TYPE_ARRAY_ARRAY',    110);
define('TYPE_ARRAY_FILE',     11);  // An array of "Files" behaves differently than other <input> arrays. TYPE_FILE handles both types.
define('TYPE_ARRAY_BINARY',   112);
define('TYPE_ARRAY_NOHTMLCOND',113);

define('TYPE_ARRAY_KEYS_INT', 202);
define('TYPE_ARRAY_KEYS_STR', 207);

define('TYPE_CONVERT_SINGLE', 100); // value to subtract from array types to convert to single types
define('TYPE_CONVERT_KEYS',   200); // value to subtract from array => keys types to convert to single types


class vH_Input_Cleaner
{
	var $registry = NULL;
	
	var $cleaned_vars = array();
	
	var $superglobal_lookup = array(
		'g' => '_GET',
		'p' => '_POST',
		'r' => '_REQUEST',
		'c' => '_COOKIE',
		's' => '_SERVER',
		'e' => '_ENV',
		'f' => '_FILES'
	);
	
	function vH_Input_Cleaner(&$registry)
	{
		$this->registry =& $registry;

		if (!is_array($GLOBALS))
		{
			die('<strong>Fatal Error:</strong> Invalid URL.');
		}
		
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			foreach (array_keys($_POST) AS $key)
			{
				if (isset($_GET["$key"]))
				{
					$_GET["$key"] = $_REQUEST["$key"] = $_POST["$key"];
				}
			}
		}
		
	}
	
	function clean_array_gpc($source, $variables)
	{
		$sg =& $GLOBALS[$this->superglobal_lookup["$source"]];
		
		foreach ($variables as $varname => $vartype)
		{
			// clean a variable only once unless its a different type
			if (!isset($this->cleaned_vars["$varname"]) OR $this->cleaned_vars["$varname"] != $vartype)
			{
				$this->registry->GPC_exists["$varname"] = isset($sg["$varname"]);
				$this->registry->GPC["$varname"] =& $this->clean( $sg["$varname"], $vartype, isset($sg["$varname"]) );
				
				if ((defined('NEED_DECODE') AND NEED_DECODE === true))
				{
					switch ($vartype) {
						case TYPE_STR:
						case TYPE_NOTRIM:
						case TYPE_NOHTML:
						case TYPE_NOHTMLCOND:
							if (!($charset = vB_Template_Runtime::fetchStyleVar('charset')))
							{
								$charset = $this->registry->userinfo['lang_charset'];
							}

							$lower_charset = strtolower($charset);
							if ($lower_charset != 'utf-8')
							{
								if ($lower_charset == 'iso-8859-1')
								{
									$this->registry->GPC["$varname"] = to_charset(ncrencode($this->registry->GPC["$varname"], true, true), 'utf-8');
								}
								else
								{
									$this->registry->GPC["$varname"] = to_charset($this->registry->GPC["$varname"], 'utf-8');
								}
							}
					}
				}
				$this->cleaned_vars["$varname"] = $vartype;
			}
		}
	}
	
	function &clean(&$var, $vartype = TYPE_NOCLEAN, $exists = true)
	{
				
		if ($exists)
		{
			if ($vartype < TYPE_CONVERT_SINGLE)
			{
				$this->do_clean($var, $vartype);
			}
			else if (is_array($var))
			{
				if ($vartype >= TYPE_CONVERT_KEYS)
				{
					$var = array_keys($var);
					$vartype -=  TYPE_CONVERT_KEYS;
				}
				else
				{
					$vartype -= TYPE_CONVERT_SINGLE;
				}

				foreach (array_keys($var) AS $key)
				{
					$this->do_clean($var["$key"], $vartype);
				}
			}
			else
			{
				$var = array();
			}
			return $var;
		}
		else
		{
			// We use $newvar here to prevent overwrite superglobals. See bug #28898.
			if ($vartype < TYPE_CONVERT_SINGLE)
			{
				switch ($vartype)
				{
					case TYPE_INT:
					case TYPE_UINT:
					case TYPE_NUM:
					case TYPE_UNUM:
					case TYPE_UNIXTIME:
					{
						$newvar = 0;
						break;
					}
					case TYPE_STR:
					case TYPE_NOHTML:
					case TYPE_NOTRIM:
					case TYPE_NOHTMLCOND:
					{
						$newvar = '';
						break;
					}
					case TYPE_BOOL:
					{
						$newvar = 0;
						break;
					}
					case TYPE_ARRAY:
					case TYPE_FILE:
					{
						$newvar = array();
						break;
					}
					case TYPE_NOCLEAN:
					{
						$newvar = null;
						break;
					}
					default:
					{
						$newvar = null;
					}
				}
			}
			else
			{
				$newvar = array();
			}

			return $newvar;
		}
	}
	
	function &do_clean(&$data, $type)
	{
		static $booltypes = array('1', 'yes', 'y', 'true', 'on');
		switch ($type)
		{
			case TYPE_INT:
				$data = intval($data);
				break;
				
			case TYPE_UINT:
				$data = ($data = intval($data)) < 0 ? 0 : $data;
				break;
				
			case TYPE_NUM:
				$data = strval($data) + 0;
				break;
				
			case TYPE_UNUM:
				$data = strval($data) + 0;
				$data = ($data < 0) ? 0 : $data;
				break;
				
			case TYPE_BINARY:
				$data = strval($data);
				break;
				
			case TYPE_STR:
				$data = trim(strval($data));
				break;
				
			case TYPE_NOTRIM:
				$data = strval($data);
				break;
				
			case TYPE_NOHTML:
				$data = htmlspecialchars_uni(trim(strval($data)));
				break;
				
			case TYPE_BOOL:
				$data = in_array(strtolower($data), $booltypes) ? 1 : 0;
				break;
				
			case TYPE_ARRAY:
				$data = (is_array($data)) ? $data : array();
				break;
				
			case TYPE_NOHTMLCOND:
			{
				$data = trim(strval($data));
				if (strcspn($data, '<>"') < strlen($data) OR (strpos($data, '&') !== false AND !preg_match('/&(#[0-9]+|amp|lt|gt|quot);/si', $data)))
				{
					// data is not htmlspecialchars because it still has characters or entities it shouldn't
					$data = htmlspecialchars_uni($data);
				}
				break;
			}
			case TYPE_FILE:
			{
				// perhaps redundant :p
				if (is_array($data))
				{
					if (is_array($data['name']))
					{
						$files = count($data['name']);
						for ($index = 0; $index < $files; $index++)
						{
							$data['name']["$index"] = trim(strval($data['name']["$index"]));
							$data['type']["$index"] = trim(strval($data['type']["$index"]));
							$data['tmp_name']["$index"] = trim(strval($data['tmp_name']["$index"]));
							$data['error']["$index"] = intval($data['error']["$index"]);
							$data['size']["$index"] = intval($data['size']["$index"]);
						}
					}
					else
					{
						$data['name'] = trim(strval($data['name']));
						$data['type'] = trim(strval($data['type']));
						$data['tmp_name'] = trim(strval($data['tmp_name']));
						$data['error'] = intval($data['error']);
						$data['size'] = intval($data['size']);
					}
				}
				else
				{
					$data = array(
						'name'     => '',
						'type'     => '',
						'tmp_name' => '',
						'error'    => 0,
						'size'     => 4, // UPLOAD_ERR_NO_FILE
					);
				}
				break;
			}
			/*case TYPE_UNIXTIME:
			{
				if (is_array($data))
				{
					$data = $this->clean($data, TYPE_ARRAY_UINT);
					if ($data['month'] AND $data['day'] AND $data['year'])
					{
						require_once(DIR . '/includes/functions_misc.php');
						$data = vbmktime($data['hour'], $data['minute'], $data['second'], $data['month'], $data['day'], $data['year']);
					}
					else
					{
						$data = 0;
					}
				}
				else
				{
					$data = ($data = intval($data)) < 0 ? 0 : $data;
				}
				break;
			}*/
			// null actions should be deifned here so we can still catch typos below
			case TYPE_NOCLEAN:
			{
				break;
			}

			default:
			{
				if ($this->registry->debug)
				{
					trigger_error('vB_Input_Cleaner::do_clean() Invalid data type specified', E_USER_WARNING);
				}
			}
		}

		// strip out characters that really have no business being in non-binary data
		switch ($type)
		{
			case TYPE_STR:
			case TYPE_NOTRIM:
			case TYPE_NOHTML:
			case TYPE_NOHTMLCOND:
				$data = str_replace(chr(0), '', $data);
		}
		return $data;
	}
	
	function fetch_ip()
	{
        return $_SERVER['REMOTE_ADDR'];
	}
	
}