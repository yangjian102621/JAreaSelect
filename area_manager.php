<?php
error_reporting(0);
$_DIR = dirname(__FILE__);
if ( !defined("OS") ) define('OS', DIRECTORY_SEPARATOR);

include $_DIR.OS.'php'.OS.'mysql_class.php';
include $_DIR.OS.'php'.OS.'/mysql_config_class.php';
include $_DIR.OS.'php'.OS.'/page_class.php';

$mysql = mysql::getInstance();
$_table = "area";
$act = trim($_GET['act']);
$id = intval($_GET['id']);
if ( $act == 'del' ) {
	if ($mysql->delete($_table, "id={$id}")) {
		$result = "删除成功！";
	}
}

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
	<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
	<title>区域列表</title>
	<style type="text/css">
		h3 {
			text-align: center;}
		.container {
			padding: 10px;
		}
		.alert {margin-bottom: 0;}
	</style>
</head>
<body>
<div class="container">
	<h3>区域列表</h3>
	<div class="container">
		<form method="post" class="form-inline" action="<?=$_SERVER['PHP_SELF']?>">
			搜索： <input type="text" name="keywords" class="form-control" value="<?=$_keywords?>" />
			<input class="btn btn-primary" type="submit" value="搜索" />
		</form>

	</div>

    <?php if($result) {?>
		<div class="alert alert-success alert-dismissible fade in" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
			<strong><?=$result?></strong>
		</div>
    <?php }?>

	<div class="container">
		<div class="btn-group" role="group">
			<a href="area_edit.php" class="btn btn-sm btn-info">添加省份</a>
			<a href="area_manager.php" class="btn btn-sm btn-info">返回列表</a>
			<a href="php/make.php" class="btn btn-sm btn-info">生成js数组文件</a>
		</div>
	</div>

	<div class="container">
		<table class="table table-bordered">
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
	</div>

	<div class="mypage_handle"><?=$_page->show_page_handle();?></div>
</div>
</body>
</html>

