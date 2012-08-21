<?php
//error_reporting(1);
session_start();
include('../globals/backEnd.php');
include('includes/global_vars.php');
include('search.class.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body style="word-wrap:break-word">
<?php
if(isset($_REQUEST['submit'])){
	$search = new FTSearch();
	$search->settable = 'qc_news';
	$search->comlumn_id = 'news_id';
	$search->searchcolumns = 'news_title, news_desc';
	$search->selectcolumns = 'news_title, news_id, news_desc';
	$search->find($_REQUEST['q']);
	$data = $search->result();
	$data_filters = $search->filters('news_title, news_desc');
}
?>
<form action="" method="get">
	Tu khoa: <input type="text" name="q" value="<?=$_REQUEST['q']?>" size="40" />
	<input type="submit" name="submit" value="Tim kiem" />
</form>
<?php
//print_r($search->columnMatch());
//print_r($data);
echo '<pre>';
print_r($data_filters);
echo '<pre>';


?>

</body>
</html>
