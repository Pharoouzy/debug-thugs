<?php

	/**
	 * If It's going to need the database, then it's 
	 *probably smart to require it before we start.
	 */

	require_once(CLS_PATH.'Database.php');

	class Login extends DatabaseObject {

		protected static $table_name = 'logins';
		protected static $db_fields = ['id', 'user_id', 'password', 'type', 'last_login',];
		public $id;
		public $user_id;
		public $password;
		public $type;
		public $last_login;


		public static function auth($data){
			global $db;
			$user_id = trim($db->escape_value(ucfirst($data['user_id'])));
			$password = trim($db->escape_value(md5($data['password'])));
			$sql = "SELECT * FROM ".static::$table_name." WHERE user_id = '{$user_id}' AND password = '{$password}' LIMIT 1";
			$result = self::find_by_sql($sql);

			return !empty($result) ? array_shift($result) : false;
		}

		public function add($user_id, $type){
			global $db;
			$this->user_id = trim($db->escape_value($user_id));
			$this->password = trim($db->escape_value(md5(strtolower($user_id))));
			$this->type = trim($db->escape_value($type));
			$this->created_at = format_db_datetime(time());

			if($this->save()){
				return true;
			}
			else{
				return false;
			}
		}

		public static function get_last_login($user_id){
			global $db;
			$lg = $db->query("SELECT * FROM ".static::$table_name." WHERE user_id = '{$user_id}' LIMIT 1");
			$login = mysqli_fetch_assoc($lg);

			return $login['last_login'];
		}
	}


?>