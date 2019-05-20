<?php 

	function redirect($location = NULL){
		if ($location != NULL) {
			header("Location: {$location}");
			exit();
		}
	}

	function message($message){
		return $message;
	}

	spl_autoload_register(function($class_name){
		$class_name = $class_name;
		$filepath = realpath(dirname(__FILE__));
		$path = LIB_PATH."{$class_name}.php";
		if (file_exists($path)) {
			include_once($path);
		}
		else{
			die("Class {$class_name} could not be found.");
		}
	});
	
	function template($template = ""){
		include(SITE_ROOT.DS.'public'.DS.'layouts'.DS.$template.'.php');
	}

	function log_action($action, $message=""){
		$filename = SITE_ROOT.DS.DS."logs".DS."logs.log";
		$new = file_exists($filename) ? false : true;
		if ($handle = fopen($filename, 'a')) {
			$datetime = date('Y-m-d H:i:s');
			$content = "[ {$datetime} ] | {$action}:- {$message}\r\n";
			fwrite($handle, $content);
			fclose($handle);
			if ($new) { chmod($filename, 0755); }
		}
		else {
			echo "Could not open log file for written.";

		}
	}

	function datetime_to_text($datetime = ""){
		$unixdatetime = strtotime($datetime);
		return strftime("%B %d, %Y at %I:%M %p", $unixdatetime);
	}

	function encrypt($data, $salt){
		$encrypted_data = crypt($data, md5($salt."4321"));

		return $encrypted_data;
	}

	function format_datetime($time){
		return date('Y-m-d H:i:s', strtotime($time));
	}

	function format_db_datetime($time){
		return date('Y-m-d H:i:s', $time);
	}

	function check_title_length($title){
		$truncator = (strlen($title) > 32) ? "..." : "";
		$title = substr($title, 0, 32).$truncator;

		return $title;
	}

	function format_date($time){
		return date("jS M, Y", strtotime($time));
	}

	function scrape_time($date){
		return date("Y-m-d", strtotime($date));
	}

?>