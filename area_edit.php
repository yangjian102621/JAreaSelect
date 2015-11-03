<?php
$_DIR = dirname(__FILE__);
if ( !defined("OS") ) define('OS', DIRECTORY_SEPARATOR);

include $_DIR.OS.'php'.OS.'mysql_class.php';
include $_DIR.OS.'php'.OS.'/mysql_config_class.php';

error_reporting(E_ALL&~E_NOTICE);

$_act = trim($_GET['act']);
$_pid = intval($_GET['pid']);
$_id = intval($_GET['id']);

$mysql = mysql::getInstance();
$_table = "area";
	
if ( $_act == 'cadd' ) {
	$_data = $_POST['data'];
	if ( $_id > 0 ) {
		if ( $mysql->update($_table, $_data, "id={$_id}") !== FALSE )
			echo '操作成功！';
	} else {
		if ( $mysql->insert($_table, $_data) !== FALSE )
			echo '操作成功！';
	}
}

if ( $_id > 0 ) {
	$_item = $mysql->getOneRow("select * from {$_table} where id={$_id}");
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="css/page_handle_pink.css" />
<title>区域管理</title>
<style>
	body {font-size:12px; padding:0px; margin:0px; text-align:center;}
	#wrap {margin:auto; width:800px;}
	.content_list_table td {padding:5px;}
</style>
</head>
<body>
	<div id="wrap">
		<h3>区域添加</h3>
		<p>
			<a href="area_manager.php">返回列表</a>
		</p>
		
		<p>
			<form method="post" action="<?=$_SERVER['PHP_SELF']?>?act=cadd&id=<?=$_id?>&pid=<?=$_pid?>">
			区域名称：<input type="text" name="data[name]" value="<?=$_item['name']?>" /> <br /><br />
			<?php if ( $_id <= 0 ) {?>
				<input type="hidden" name="data[pid]" value="<?=$_pid?>" />
			<?php }?>
			<input type="submit" value="提交保存" />
			</form>
		</p>
		
	</div>
</body>
</html>

