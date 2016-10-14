<?php

/**

 * the configuration message for mysql database server

 * @access  yangjian

 * @version 1.0

 * @link    http://www.webssky.com

 */

if ( !defined('DEFAULT_CACHE_DIR') ) {

	define('DEFAULT_CACHE_DIR',dirname(__FILE__).DIRECTORY_SEPARATOR.'my_cache');							  	

}

class mysqli_config {

	/**

	 * TRUE for try pconnect 

	 */

	public static  $pconnect = FALSE;

	

	/**

	/**

	 * the host of the mysql server 

	 */

	public static  $db_host = 'localhost';

	

	/**

	 * the username for mysql server

	 */

	public static $db_user = 'root';

	

	/**

	 * the password of the username

	 */

	public static $db_pass = '123456';

	

	/**

	 * the database name

	 */

	public static $db_data = 'test';

	

	/**

	 * the charset 

	 */

	public static $charset = 'utf8';

	

	/**

	 * default directory of cache file (缓存的默认地址)

	 */

	public static $default_cache_dir = DEFAULT_CACHE_DIR;

	/**

	 * set cache timeout(设置缓存时间：默认为2个小时)

	 */

	public static $cache_timeout = 10;

	/**

	 * identifier of sql(sql语句的标志符号，含有@表示是getOneRow()方法的sql语句)

	 */

	public static $sql_id = '@';

	

}

?>