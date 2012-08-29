<?php
/*$zip = new ZipArchive;
if ($zip->open('zip.zip') === TRUE) {
    $zip->extractTo('./unzip/1/2/3');
    $zip->close();
    echo 'ok';
} else {
    echo 'failed';
}
*/

function arr_encode($arr)
{
	foreach($arr as $k=>$v)
	{
		if(is_array($v)){
			$v = arr_encode($v);
			$arr[$k] = $v;
		}else{
			$v = base64_encode($v);
			$arr[$k] = $v;
		}
	}
	return $arr;
}

function arr_decode($arr)
{
	foreach($arr as $k=>$v)
	{
		if(is_array($v)){
			$v = arr_decode($v);
			$arr[$k] = $v;
		}else{
			$v = base64_decode($v);
			$arr[$k] = $v;
		}
	}
	return $arr;
}

$a = array(
	'01'=>'cong',
	'02'=>array(
		'2.1'=>'hoa',
		'2.2'=>'xa',
		'2.3'=>'hoi',
		'2.4'=>array(
			'2.4.1'=>'chu',
			'2.4.2'=>array('nghia','viet','nam'),
			'2.4.3'=>'doc',
			'2.4.4'=>'lap',
			'2.4.5'=>'tu',
			'2.4.6'=>'do',
			'2.4.7'=>'hanh',
			'2.4.8'=>'phuc'
		)
	)
);
$en = serialize(arr_encode($a));
$de = arr_decode(unserialize($en));
print_r($de);
?>