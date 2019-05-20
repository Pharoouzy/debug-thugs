<?php

	/**
	 * If It's going to need the database, then it's 
	 *probably smart to require it before we start.
	 */

	require_once(CLS_PATH.'Database.php');

	class Department extends DatabaseObject {

		protected static $table_name = 'departments';
		protected static $db_fields = ['id', 'dept_name', 'specialization'];
		public $id;
		public $dept_name;
		public $specialization;



		public function add($data){
			$this->dept_name = ucfirst($data['dept_name']);
			$this->specialization = $data['specialization'];

			if($this->save()){
				return true;
			}
			else{
				return false;
			}
		}

		public function total(){
			global $db;
			$total = $db->query("SELECT * FROM ".static::$table_name);

			return $total->num_rows;
		}

		public static function get_name($id){
			global $db;
			$dept = $db->query("SELECT * FROM ".static::$table_name." WHERE id = '{$id}' LIMIT 1");
			$department = mysqli_fetch_assoc($dept);

			return $department['dept_name'];
		}
	}


?>