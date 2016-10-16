<?php

$base_dir = dirname(dirname(__FILE__));

if ( !defined('OS') ) {

	define('OS',DIRECTORY_SEPARATOR);

}



require_once($base_dir.OS.'mysql'.OS.'libs.php');

require_once($base_dir.OS.'page_class'.OS.'page_class.php');

$mysql = mysql_i::getInstance();

$sql = "select Id from user ";

$rows_num = $mysql->get_rows_num($sql);

$page = new page($rows_num,10,$_GET['pageNow']);

$sql = $sql = "select * from user ".$page->limit."";

$result = $mysql->getList($sql);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>分页类测试</title>

<link href="css/page_handle_pink.css" rel="stylesheet" type="text/css" />

</head>



<body>

<table>

	<?php

    foreach ( $result as $_val ) {

	?>

	<tr>

    	<td><?=$_val['username']?></td>

        <td><?=$_val['pass']?></td>

        <td><?=$_val['email']?></td>

        <td><?=$_val['sex']?></td>

    </tr>

    <?php

	}

	?>

    

    <tr><td colspan="4"><?=$page->show_page_handle();?></td></tr>

</table>

</body>

</html>