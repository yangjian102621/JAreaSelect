<?php

/**

 * the public class for mysql database;

 * @author    yangjian<yangjian102621@gmail.com>;  <br />

 * @link      http://www.webssky.com; <br />

 * @version   1.0 ; <br />

 * @copyright webssky all right Reserved <br />

 */



class mysql {

	/**
	 * true for debug
	 */	

	private $debug = false;



	/**

	 * the resource of connected to the database 

	 */

	private $link = NULL;

	

	/**

	 * the only quote to the class

	 */

	private static $_instance = NULL;

	

	

	/**

	 * connected to the database server

	 * and do some query work to unify the charset

	 */

	private function connect() {

		if ( mysqli_config::$pconnect ) {

			$this->link = mysqli_pconnect(mysqli_config::$db_host, mysqli_config::$db_user,

				 mysqli_config::$db_pass);

		} else {

			$this->link = mysqli_connect(mysqli_config::$db_host, mysqli_config::$db_user,

				 mysqli_config::$db_pass);

		}

		

		if ( $this->link == FALSE ) {

			$this->fatalError("cannot connected to the database server！");

		}

		

		// set the default timezone

		date_default_timezone_set("PRC");

		

		//set the charset

		mysqli_query($this->link, "SET NAMES '".mysqli_config::$charset."'");

		mysqli_query($this->link, "SET CHARACTER_SET_CLIENT='".mysqli_config::$charset."'");

		mysqli_query($this->link, "SET CHARACTER_SET_RESULTS='".mysqli_config::$charset."'");

		

		//select the pointed database

		if ( ! mysqli_select_db($this->link, mysqli_config::$db_data ) ) {

			$this->fatalError("cannot selected the given database ！");

		}

	}

	

	/**

	 * get the quoto the class

	 * @return mysql : the quote to the class

	 */

	public static function getInstance() {

		if ( ! self::$_instance instanceof self ) {

			self::$_instance = new self();

		}

		return self::$_instance;

	}

	

	/**

	 * in case of the clone for the class

	 */

	public function __clone() {

		//do nothing here

	}

	

	/**

	 * send an query string to the mysql server	<br />

	 * 

	 * @param string $query:the query string 	<br />

	 * @return TRUE for success FALSE for failed <br />

	 */

	public function query($query = NULL) {

		

		if (isset($query) && $query != NULL ) {

			//connect to the database server

			if ( $this->link == NULL ) {

				$this->connect();

			}

			//print the query string for debug

			if ( $this->debug == TRUE ) {

				echo '<p>query string: '.$query.'</p>';

			}



			if ( ($result = mysqli_query($this->link, $query) ) !== FALSE ) {

				return $result;

			}

		}

		return FALSE;

	}

	

	/**

	 * Affair query

	 * @param array

	 */

	public function affairQuery($array) {

		$this->query('SET AUTOCOMMIT = 0');

		$this->query('BEGIN');

		$result = true;

		foreach ($array as $name => $args) {

			//echo $name .','. implode('|', $args).'<br />';

			if (call_user_method_array($name, $this, $args) === FALSE ) {

				$result = false;

			}

		}

		if ($result) {

			$this->query('COMMIT');  //commit the query collection	

		} else {

			$this->query('ROLLBACK');	

		}

		$this->query('SET AUTOCOMMIT = 1');

		return $result;

	}

	

	/**

	 * insert the given data into the database <br />

	 * 

	 * @param string $table_name              <br />

	 * @param array  $field_vars           <br />

	 * @return TRUE for success FALSE for failed <br />

	 * $field_vars with a style like array('field_name'=>'field_value')

	 */

	public function insert($table_name, $field_vars = array()) {

		

		if ( isset($table_name) && !empty($field_vars) ) {

			$keys = NULL;

			$key_var = NULL;

			$fields = $this->getFields($table_name);

			foreach ( $field_vars as $name => $values ) {

				if ( in_array($name, $fields) ) {

					if ($keys == NULL) {

						$keys .= $name;

					} else {

						$keys .= ', '.$name;

					}

					

					if ($key_var == NULL) {

						$key_var .= '\''.$values. '\'';

					} else {

						$key_var .= ', \''.$values. '\'';

					}

				}

			}



			if ( $keys !== NULL ) {

				$query = "INSERT INTO ".$table_name." (" . $keys . ") VALUES (" . $key_var . ")";

				if ( $this->query($query) ) {

					return $this->getInsertId();

				}

			}

		}

		return FALSE;

	}

	

	/**

	 * delete the pointed record

	 * 

	 * @param string $table_name; <br />

	 * @param string $where       <br />

	 * @return boolean TRUE for success FALSE for failed <br />

	 * if the $where equals ALL all the records in table_name will be deleted

	 */

	public function delete($table_name, $where){

		if ( isset($table_name) && isset($where) ) {

			if ($where == 'ALL') {

				$query = "DELETE FROM ".$table_name."";

			} else {

				$query = "DELETE FROM ".$table_name." WHERE ".$where."";

			}

			

			if ( $this->query($query) ) {

				return TRUE;

			}

		}

		return FALSE;

	}

	

	

	/**

	 * get the query result

	 * 

	 * @param string $query: the query string <br />

	 * @return array for success FALSE for failed

	 */

	public function getList( $query = NULL){

		if (isset($query) && $query != NULL) {

			$result = $this->query( $query );

			if ( $result != FALSE ) {

				return $this->formatQueryResult($result);

			}

		}

		return array();

	}

	

	/**

	 * update the pointed record

	 * 

	 * @param string $table_name; <br />

	 * @param string $filed_vars; <br />

	 * @param string $where       <br />

	 * $filed_vars with a style like array('field'=>'value');

	 */

	public function update($table_name, $field_vars, $where){

		if ( isset($table_name) && isset($field_vars) && isset($where) ) {

			$key_var_pair = NULL;

			$fields = $this->getFields($table_name);

			

			foreach ( $field_vars as $name => $values ) {

				if ( in_array($name, $fields) ) {

					if ($key_var_pair == NULL) {

						$key_var_pair .= $name.' = \''.$values.'\'';

					} else {

						$key_var_pair .= ', '.$name.' = \''.$values.'\'';

					}

				}

			}

			

			if ( $key_var_pair !== NULL ) {

				$query = " UPDATE " . $table_name . " SET  ".$key_var_pair."  WHERE " . $where. "";
				if ( $this->query( $query ) ) {

					return mysqli_affected_rows($this->link);

				}

			}

		}

		return FALSE;

	}

	

	

	/**

	 * get the pointed record

	 *

	 * @param string $query: the query string <br />

	 * @return array for success FALSE for failed <br />

	 */	

	function getOneRow($query) {

		if ( isset($query) ) {

			//get data from the database

			$result = $this->query ( $query );

			if ( $result != FALSE ) {

				return mysqli_fetch_assoc( $result );

			} 

		}

		return FALSE;

	}	

	

	

	/**

	 * get the total number of the record <br />

	 * 

	 * @param string $query               <br />

	 * @return int $total Number          <br />

	 */

	public function getRowsNum($query) {

		if ( isset($query) ) {

			$result = $this->query( $query );

			if ($result) {

				return mysqli_num_rows($result);

			}

		}

		return 0;

	}

	

	

	/**

	 * get the limit result(select *** limit $start, $offset) <br />

	 * 

	 * @param Integer $query: the query string <br />

	 * @param Integer $numRows: the total number of the record that the query is to get 

	 * @param Integer $offset:  查询偏移量<br />

	 * @return FALSE for failed and Array for success

	 * if we want to display the record in several pages, $offset equals $pageNow - 1;

	 */

	public function selectLimit($query, $pagesize = 10, $offset = 0){

		if ( isset($query) && isset($pagesize) ) {

			if ( $offset == 0 ) {

				$query .= " LIMIT 0, " . $pagesize;

			} else if ( is_numeric($offset) && is_numeric($pagesize) ) {

				$query .= " LIMIT " . ( $pagesize * $offset ) . ", " . $pagesize;

			}

			return $this->getList( $query );

		}

	}

	

	

	/**

	 * format the query result <br />

	 * 

	 * @param resource $result: mysql query result(Resource) <br />

	 * @return array $array : the result after formated <br />

	 */

	private function formatQueryResult( $result ){

		$array = array();

		if (isset($result) && $result != FALSE) {

			$i = 0;

			while ($rows = mysqli_fetch_assoc($result)) {

				$array[$i] = $rows;

				$i++;

			}

		}

		return $array;

	}

	

	

	/**

	 * get all the fields of the pointed table

	 * @param string $db_table <br />

	 * @return array $fileds_array   <br />

	 */

	private function getFields($table_name = NULL){

		$fileds_array = array();

		if ( isset($table_name) && $table_name != NULL ) {

			if ( ($result = $this->query('SHOW COLUMNS FROM '.$table_name.'')) != FALSE ) {

				$i = 0;

				while ( ($rows = mysqli_fetch_row($result)) != FALSE ) {

					$fileds_array[$i] = $rows[0];

					$i++;

				}

			}

		}

		return $fileds_array;

	}

	

	/**

	 * return the latest inserted id <br />

	 * @return Integer $Id 			 <br />

	 */

	public function getInsertId() {

		return mysqli_insert_id( $this->link );

	}

	

	/**

	 * show some fantl error <br />

	 * @param string $msg <br />

	 */

	private function fatalError($msg) {

		exit($msg);

	}

	

	/**

	 * show some waring <br />

	 * @param string $msg <br />

	 */

	private function warnError($msg) {

		echo '<p style="color: red;">'.$msg."</p>";

	}

	

	/**

	 * print something

	 * @param string $msg <br />

	 */

	private function println($msg){

		echo '<p>'.$msg.'</p>';

	}

	

	public function __destruct() {

		if ($this->link != NULL) {

			mysqli_close($this->link);

		}

	}

	

}

?>