<?php

	require_once(LIB_PATH.'config.php');

	/**
	 * Database Class
	 */	

	class Database {
		private $connection;
		public $last_query;
		private $real_escape_str_exists;
		private $magic_quotes_active;

		function __construct() {
			$this->open_connection();
			$this->magic_quotes_active = get_magic_quotes_gpc();
			$this->real_escape_str_exists = function_exists('mysqli_real_escape_string');// i.e PHP >= v4.3.0
		}

		public function open_connection() {
			$this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
			if (!$this->connection) {
				die("Database connection failed: ". mysqli_error());
			} else{
				$db_select = mysqli_select_db($this->connection, DB_NAME);
				if (!$db_select) {
					die("Database selection failed: ".mysqli_error());
				}
			}
		}

		public function close_connection(){
			if (isset($this->connection)) {
				mysqli_close($this->connection);
				unset($this->connection);
			}
		}

		public function query($sql){
			$this->last_query = $sql;
			$result = mysqli_query($this->connection, $sql);
			$this->confirm_query($result);
			return $result;
		}

		public function fetch_array($result){
			return mysqli_fetch_array($result);
		}
		
		public function num_rows($result){
			return mysqli_num_rows($result);
		}

		public function insert_id(){
			// get the last id inserted over the current db connection
			return mysqli_insert_id($this->connection);
		}

		public function affected_rows(){
			return mysqli_affected_rows($this->connection);
		}

		private function confirm_query($result){
			if (!$result) {
				$output = 'Database query failed: '.mysqli_error($this->connection).'<br>';
				// $output .= 'Last SQL query: '. $this->last_query;
				die($output); 
				return false;
			}
			else{
				return true;
			}
		}

		// Prepare values for submission to sql
		public function escape_value($value){
			if ($this->real_escape_str_exists) { // PHP v3.4.0 or Higher
				// undo any magic quote effects so mysqli_real_escape_string can do the work
				if ($this->magic_quotes_active) {
					$value = stripslashes($value);
				}
				$value = mysqli_real_escape_string($this->connection, $value);
			}
			else{// before PHP v4.3.0
				// if magic quotes aren't already on then add slashes manually
				if (!$magic_quotes_active) {
					$value = addslashes($value);
				}
				// if magic quotes are active, then slashes already exist
			}

			return $value;

		}

		public function check($value, $table){
			$this->query("SELECT * FROM $table WHERE value='$value'");
		}
	}

	$db = new Database();

?>