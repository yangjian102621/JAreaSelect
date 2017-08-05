<?php

/**

 * 整理数据,生产JavaScript数组文件

 * @author		yangjian<yangjian102621@gmail.com>

 */

include 'mysql_class.php';

include 'mysql_config_class.php';

header("Content-Type:text/html; charset=UTF-8");

//连接数据库

$mysql = mysql::getInstance();

$_table = "area";

//$list = $mysql->getList("SELECT * FROM `area` WHERE pid IN (SELECT id FROM area WHERE `name` in('北京','天津', '上海','重庆'))");
//
//foreach ( $list as $value ) {
//	$data['pid'] = $value['pid'];
//	var_dump($mysql->update('area', $data, "pid={$value['id']}"));
//}
//
//die();

//var_dump($mysql);


/**
 * 生成JavaScript数组
 */
$timer = timer();
$province = $mysql->getList("select * from {$_table} where pid=0");

$p = ''; //省份
$c = ''; //城市
$d = ''; //地区
foreach ( $province as $value ) {
	if ( $p == '' ) {
		$p .= "'{$value['id']}':'{$value['name']}'";
	} else {
		$p .= ", '{$value['id']}':'{$value['name']}'";
	}

	$citys = $mysql->getList("select * from {$_table} where pid={$value["id"]}");
	if ( $c == '' ) {
		$c .= "'{$value['id']}' : [";
	} else {
		$c .= ", '{$value['id']}' : [";
	}
	$cc = array();
	foreach ($citys as $city) {
		$cc[] = "{'id':'{$city['id']}', 'name':'{$city['name']}'}";

		$regions = $mysql->getList("select * from {$_table} where pid={$city["id"]}");
		if ( $d == '' ) {
			$d .= "'{$city['id']}' : [";
		} else {
			$d .= ", '{$city['id']}' : [";
		}
		$gg = array();
		foreach ($regions as $region) {
			$gg[] = "{'id':'{$region['id']}', 'name':'{$region['name']}'}";
		}
		$d .= implode(", ", $gg)."]";
	}
	$c .= implode(", ", $cc)."]";

}
$buffer = "var __AREADATA__ = {\n";
$buffer .= "\t'prov' : {";
$buffer .= $p."}, \n";
$buffer .= "\t'city' : {";
$buffer .= $c."}, \n";
$buffer .= "\t'dist' : {";
$buffer .= $d."}, \n";
$buffer .= "};";

echo timer() - $timer;
if ( file_put_contents("../js/JAreaData.js", $buffer) !== FALSE ) {

	echo '生成文件成功！';

}

/* print message */

function __print( $_msg ) {

	echo '<pre>';

	print_r($_msg);

	echo '</pre>';

}

function timer() {
	list($msec, $sec) = explode(' ', microtime());
	return ((float)$msec + (float)$sec);
}
?>

