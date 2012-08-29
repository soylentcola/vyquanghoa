<?php
	define('local', true); //true, false
	
	$config['Database']['dbtype'] = 'mysql';
	$config['Database']['tableprefix'] = 'th_';	
	$config['Database']['host'] = 'localhost';	
	$config['Database']['port'] = 3306;
	
	if( local ){
		$config['Database']['dbname'] = 'web_thuongha';
		$config['Database']['username'] = 'root';
		$config['Database']['password'] = '15987530012';
		$config['Misc']['sitepath'] = '/vnpec/htaccess/';
	}else{
		$config['Database']['dbname'] = 'nhqual0m_vhoa';
		$config['Database']['username'] = 'nhqual0m_vhoa';
		$config['Database']['password'] = 'vhoa15987530012';
		$config['Misc']['sitepath'] = '/';
	}	
	
	$config['Misc']['cookieprefix'] = 'th';
	$config['Misc']['admindir'] = 'admincp';
	$config['Misc']['page_group_size'] = 10;

	//meta tag
	$config['meta']['meta_t'] = 'Nem chua Thương Hà'; //title
	$config['meta']['meta_d'] = 'Đặc sản nem chua Thanh Hóa, nem chua Thương Hà'; //meta description
	$config['meta']['meta_k'] = 'Nem chua Thương Hà, đặc sản'; //meta keywords
	
	