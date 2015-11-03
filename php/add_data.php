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

//var_dump($mysql);


/**

 * 生成JavaScript数组

 */

$_script = <<<EOF

var AREA_DATA = {	

EOF;

$_country_str = "";

$_province_str = "";

$_city_str = "";

$_district_str = "";



$_country = $mysql->getList("select * from {$_table} where pid=0");



foreach ( $_country as $_c ) {

	//country array

	if ( $_country_str == '' ) {

		$_country_str .= "country : [['{$_c['id']}', '{$_c['name']}']";

	} else {

		$_country_str .= ", ['{$_c['id']}', '{$_c['name']}']";

	}

	

	//province array

	if ( $_province_str == '' ) {

		$_province_str .= "province : { '{$_c['id']}' : [";

	} else {

		$_province_str .= ", {$_c['id']} : [";

	}

	$_provinces = $mysql->getList("select * from {$_table} where pid={$_c['id']}");

	$_province_one = "";

	foreach ( $_provinces as $_p ) {

		

		if ( $_province_one == '' ) {

			$_province_one .= "['{$_p['id']}', '{$_p['name']}']";

		} else {

			$_province_one .= ",['{$_p['id']}', '{$_p['name']}']";

		}

		

		//city array

		if ( $_city_str == '' ) {

			$_city_str .= "city : { '{$_p['id']}' : [";

		} else {

			$_city_str .= ", '{$_p['id']}' : [";

		}

		$_citys = $mysql->getList("select * from {$_table} where pid={$_p['id']}");

		$_city_one = '';

		foreach ( $_citys as $_cc ) {

			if ( $_city_one == '' ) {

				$_city_one .= "['{$_cc['id']}', '{$_cc['name']}']";

			} else {

				$_city_one .= ", ['{$_cc['id']}', '{$_cc['name']}']";

			}

			

			//district array

			if ( $_district_str == '' ) {

				$_district_str .= "district : { '{$_cc['id']}' : [";

			} else {

				$_district_str .= ", '{$_cc['id']}' : [";

			}

			$_districts = $mysql->getList("select * from {$_table} where pid={$_cc['id']}");

			$_dd_one = '';

			foreach ( $_districts as $_dd ) {

				if ( $_dd_one == '' ) {

					$_dd_one .= "['{$_dd['id']}', '{$_dd['name']}']";

				} else {

					$_dd_one .= ", ['{$_dd['id']}', '{$_dd['name']}']";

				}

			}

			$_district_str .= $_dd_one;

			$_district_str .= "]";

		}

		$_city_str .= $_city_one;

		$_city_str .= "]";

	}

	$_province_str .= $_province_one;

	$_province_str .= "]";

}



$_country_str .= "],";

$_province_str .= "},";

$_city_str .= "},";

$_district_str .= "}";



$_script .= <<<EOF

	

	//国家

	$_country_str

EOF;



$_script .= <<<EOF

	

	//省份

	$_province_str

EOF;

	

$_script .= <<<EOF

	

	//城市

	$_city_str

EOF;



$_script .= <<<EOF

	

	//地区

	$_district_str

EOF;



$_script .= <<<EOF

					

};

EOF;

//echo $_country_str;

//echo $_province_str;

//echo $_city_str;

//echo $_district_str;

//echo $_script;



if ( file_put_contents("../js/JAreaData.js", $_script) !== FALSE ) {

	echo '生成文件成功！';

}



/* print message */

function __print( $_msg ) {

	echo '<pre>';

	print_r($_msg);

	echo '</pre>';

}

?>

