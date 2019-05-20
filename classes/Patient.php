<?php

	/**
	 * If It's going to need the database, then it's 
	 *	probably smart to require it before we start.
	 */

	require_once(CLS_PATH.'Database.php');

	class Patient extends DatabaseObject {

		protected static $table_name = 'patients';
		protected static $db_fields = ['id', 'lastname', 'dob', 'gender', 'firstname', 'othername', 'email', 'next_of_kin', 'address', 'created_at', 'updated_at', 'blood_group'];
		public $id, $gender, $firstname, $dob, $lastname, $othername, $email, $next_of_kin, $address, $created_at, $updated_at, $blood_group;

		public function fullname(){
			if (isset($this->firstname) && isset($this->lastname)) {
				return strtoupper($this->lastname). ", ".$this->firstname . " " . $this->othername;
			}
			else{
				return "";
			}
		}

		public function add($data){
			$this->firstname = ucfirst($data['firstname']);
			$this->lastname = ucfirst($data['lastname']);
			$this->othername = ucfirst($data['othername']);
			$this->email = $data['email'];
			$this->next_of_kin = $data['next_of_kin'];
			$this->blood_group = $data['blood_group'];
			$this->address = $data['address'];
			$this->created_at = format_db_datetime(time());
			$login = new Login();

			if ($this->check_email($this->email)) {
				return false;
			}
			else {
				if($this->save() && $login->add($this->email, 'P')){
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


		public static function get_fullname($email){
			global $db;
			$pat = $db->query("SELECT * FROM ".static::$table_name." WHERE email = '{$email}' LIMIT 1");
			$patient = mysqli_fetch_assoc($pat);

			return strtoupper($patient['lastname']) . ", ". $patient['firstname'] . " ". $patient['othername'];
		}
	}


?>