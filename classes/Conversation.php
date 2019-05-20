<?php

	/**
	 *  Class COurse
	 */

	require_once(CLS_PATH.'Database.php');

	class Conversation extends DatabaseObject {

		protected static $table_name = 'conversations';
		protected static $db_fields = ['id', 'message', 'user_id','created_at'];
		public $id;
		public $message;
		public $user_id;
		public $created_at;

		public function add($data){
			$this->message = $data['message'];
			$this->user_id = $data['user_id'];
			$this->created_at = format_db_datetime(time());

			if($this->save()){
				return true;
			}
			else{
				return false;
			}
		}

		public function comment_reply($data){
			global $db;
			$comment_id = (int)$data['comment_id'];
			$reply_id = (int)$data['reply_id'];
			$query = "INSERT INTO comment_replies (comment_id, reply_id) VALUES ($comment_id, $reply_id)";

			if ($db->query($query)) {
				return true;
			}
			else{
				return false;
			}		}

		public function total(){
			global $db;
			$total = $db->query("SELECT * FROM ".static::$table_name);

			return $total->num_rows;
		}

		public static function get_total_messages($user_id){
			global $db;
			$total = $db->query("SELECT * FROM ".static::$table_name." WHERE user_id = '{$user_id}'");

			return $total->num_rows;
		}

		public static function get_message_by_user($user_id){
			return static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE user_id='{$user_id}' ORDER BY created_at DESC");
		}

		public static function formatDuration($start, $end=null){
			if (!($start instanceof DateTime)) {
				$start = new DateTime($start);
			}

			if ($end === null) {
				$end = new DateTime();
			}

			if (!($end instanceof DateTime)) {
				$end = new DateTime($end);
			}

			$interval = $end->diff($start);

			$doPlural = function($nb, $str){
				return $nb > 1 ? $str.'s' : $str;
			};

			$format = [];
			if ($interval->y !== 0) {
				$format[] = "%y ".$doPlural($interval->y, "year");
			}

			if ($interval->m !== 0) {
				$format[] = "%m ".$doPlural($interval->m, "month");
			}

			if ($interval->d !== 0) {
				$format[] = "%d ".$doPlural($interval->d, "day");
			}

			if ($interval->h !== 0) {
				$format[] = "%h ".$doPlural($interval->h, "hour");
			}

			if ($interval->i !== 0) {
				$format[] = "%i ".$doPlural($interval->i, "minute");
			}

			if ($interval->s !== 0) {
				if (!count($format)) {
					return "less than a minute ago";
				}
				else{
					$format[] = "%s ".$doPlural($interval->s, "second");
				}
			}

			if (count($format) > 1) {
				$format = array_shift($format)." and ".array_shift($format);
			}
			else{
				$format = array_pop($format);
			}

			// Prepend 'since'
			return $interval->format($format)." ago";
		}
	}


?>