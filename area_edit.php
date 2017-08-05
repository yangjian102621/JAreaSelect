<?php
$_DIR = dirname(__FILE__);
if ( !defined("OS") ) define('OS', DIRECTORY_SEPARATOR);

include $_DIR.OS.'php'.OS.'mysql_class.php';
include $_DIR.OS.'php'.OS.'/mysql_config_class.php';

error_reporting(0);

$_act = trim($_GET['act']);
$_pid = intval($_GET['pid']);
$_id = intval($_GET['id']);

$mysql = mysql::getInstance();
$_table = "area";
if ( $_act == 'cadd' ) {
    $_data = $_POST['data'];
    if ( $_id > 0 ) {
        if ( $mysql->update($_table, $_data, "id={$_id}") !== FALSE ) {
            $result = "更新成功！";
		}
    } else {
        if ( $mysql->insert($_table, $_data) !== FALSE ) {
            $result = "添加成功！";
		}

    }
}

if ( $_id > 0 ) {
    $_item = $mysql->getOneRow("select * from {$_table} where id={$_id}");
} else if ($_pid > 0) {
    $pitem = $mysql->getOneRow("select * from {$_table} where id={$_pid}");
}
?>

<!DOCTYPE>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
	<title>区域管理</title>
	<style>
		h3 {
			text-align: center;}
		.container {
			padding: 10px;
		}
	</style>
</head>
<body>
<div class="container">
	<h3><?php if ($_id>0) {?>修改地区<?php } else {?>添加地区<?php }?></h3>

	<div class="container">
		<div class="btn-group" role="group">
			<a href="area_manager.php" class="btn btn-sm btn-info">返回列表</a>
			<a href="php/make.php" class="btn btn-sm btn-info">生成js数组文件</a>
		</div>
	</div>

	<div class="container">

		<?php if($result) {?>
		<div class="alert alert-success alert-dismissible fade in" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
			<strong><?=$result?></strong>
		</div>
		<?php }?>
		
		<form method="post" action="<?=$_SERVER['PHP_SELF']?>?act=cadd&id=<?=$_id?>&pid=<?=$_pid?>">

			<?php if ($_pid > 0) {?>
				<div style="margin-bottom: 10px;">
					<button class="btn btn-primary">父级地区：<?=$pitem['name']?></button>
				</div>
			<?php }?>
			<div class="input-group">
				<input type="text" class="form-control" placeholder="区域名称" name="data[name]" value="<?=$_item['name']?>" />
                <?php if ( $_id <= 0 ) {?>
					<input type="hidden" name="data[pid]" value="<?=$_pid?>" />
                <?php }?>
				<span class="input-group-btn">
					<button type="submit" class="btn btn-success" type="button">提交保存</button>
				</span>
			</div>
		</form>
	</div>

</div>
</body>
</html>

