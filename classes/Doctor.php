<?php

	/**
	 * If It's going to need the database, then it's 
	 *probably smart to require it before we start.
	 */

	require_once(CLS_PATH.'Database.php');

	class Doctor extends DatabaseObject {

		protected static $table_name = 'doctors';
		protected static $db_fields = ['id', 'firstname', 'othername', 'lastname', 'dept_id', 'email', 'created_at', 'updated_at'];
		public $id;
		public $firstname;
		public $lastname;
		public $othername;
		public $dept_id;
		public $email;
		public $created_at;
		public $updated_at;

		public function add($data){
			global $db;
			$this->firstname = trim($db->escape_value(ucfirst(ucfirst($data['firstname']))));
			$this->lastname = trim($db->escape_value(ucfirst(ucfirst($data['lastname']))));
			$this->othername = trim($db->escape_value(ucfirst(ucfirst($data['othername']))));
			$this->dept_id = trim($db->escape_value(ucfirst(ucfirst($data['dept_id']))));
			$this->email = $this->generate_email();
			$this->created_at = format_db_datetime(time());
			$login = new Login();
			if ($this->check_email($this->email)) {
				return false;
			}
			else {
				if($this->save() && $login->add($this->generate_email(), 'D')){
					return true;
				}
				else{
					return false;
				}
			}
		}

		public function fullname(){

			if (isset($this->firstname) && isset($this->lastname)) {
				return "Dr. ". substr($this->firstname, 0, 1) . ". ". substr($this->othername, 0, 1) . ". " . $this->lastname;
			}
			else{
				return "";
			}
		}

		public function total(){
			global $db;
			$total = $db->query("SELECT * FROM ".static::$table_name);

			return $total->num_rows;
		}

		public function generate_email(){
			return strtolower('doctor'.rand(111, 999).'@telemedicine.com');
		}

		public static function get_fullname($email){
			global $db;
			$doc = $db->query("SELECT * FROM ".static::$table_name." WHERE email = '{$email}' LIMIT 1");
			$doctor = mysqli_fetch_assoc($doc);
			return $doctor['entitlement'] . " ". substr($doctor['firstname'], 0, 1) . ". ". substr($doctor['othername'], 0, 1) . ". " . $doctor['lastname'];
		}
	}


?>