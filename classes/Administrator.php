<?php

	/**
	 * Administrator Class
	 *
	 */

	require_once(CLS_PATH.'Database.php');

	class Administrator extends DatabaseObject {

		protected static $table_name = 'administrators';
		protected static $db_fields = ['id', 'username', 'email', 'created_at'];
		public $id;
		public $username;
		public $email;
		public $created_at;

		public function add($data){
			$this->username = ucfirst($data['username']);
			$this->email = $data['email'];
			$this->created_at = format_db_datetime(time());

			if ($this->check_username($this->username)) {
				return false;
			}
			else {
				if($this->save()){
					return true;
				}
				else{
					return false;
				}
			}
		}

		public function total(){
			global $db;
			$total = $db->query("SELECT * FROM ".static::$table_name);

			return $total->num_rows;
		}
	}


?>
