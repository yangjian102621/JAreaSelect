<?php
$_DIR = dirname(__FILE__);
if ( !defined("OS") ) define('OS', DIRECTORY_SEPARATOR);

include $_DIR.OS.'php'.OS.'mysql_class.php';
include $_DIR.OS.'php'.OS.'/mysql_config_class.php';
include $_DIR.OS.'php'.OS.'/page_class.php';

error_reporting(E_ALL&~E_NOTICE);

$mysql = mysql::getInstance();
$_table = "area";
$sql = "select id from {$_table} ";
$rows_num = $mysql->getRowsNum($sql);
$_page = new page($rows_num, 20,$_GET['pageNow']);
$_sql = "select * from {$_table} where 1=1";

$_keywords = trim($_POST['keywords']);
if ( $_keywords != '' ) {
	$_sql .= " and name like '%{$_keywords}%'";
}

$_pid = intval($_GET['pid']);
if ( $_pid > 0 ) {
	$_sql .= " and pid={$_pid}";
}

$_sql .= " order by pid ASC";

$_sql .= $_page->limit;
$_result = $mysql->getList($_sql);
//__print($_result);

function __print( $_a ) {
	echo '<pre>';
	print_r($_a);
	echo '</pre>';
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
		<h3>区域管理</h3>
		<div id="search_box">
			<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
			搜索： <input type="text" name="keywords" class="" value="<?=$_keywords?>" />
			<input type="submit" value="搜索" />
			</form>
			
		</div>
		
		<p style="text-align: left;">
			<a href="area_edit.php">添加国家</a>
			<a href="area_manager.php">返回列表</a>	
			<a href="php/add_data.php">生成js数组文件</a>
		</p>
		
		<table cellpadding="0" cellspacing="0" border="1" width="100%" class="content_list_table">
			<tr>
				<th>ID</th>
				<th>区域名称</th>
				<th>父级id</th>
				<th>操作</th>
			</tr>
			<?php
			foreach ( $_result as $_val ) {
			?>
			<tr>
				<td><?=$_val['id']?></td>
				<td><?=$_val['name']?></td>
				<td><?=$_val['pid']?></td>
				<td>
					<a href="area_edit.php?id=<?=$_val['id']?>">编辑</a>
					| <a href="<?=$_SERVER['PHP_SELF']?>?act=del&id=<?=$_val['id']?>">删除</a>
					| <a href="area_edit.php?pid=<?=$_val['id']?>">添加子类地区</a>
					| <a href="<?=$_SERVER['PHP_SELF']?>?pid=<?=$_val['id']?>">查看子地区</a>
				</td>
			</tr>
			<?php
			}
			?>
		</table>
		
		<div class="mypage_handle"><?=$_page->show_page_handle();?></div>
	</div>
</body>
</html>

