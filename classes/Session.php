<?php 
	/**
	 * A class to help work with Sessions
	 * In our case, primarily to manage logging users in and out
	 *
	 * Keep in mind when working with Sessions that it is generally
	 * inadvisable to store DB-related Objects in Sessions
	 */
	class Session {

		private $loggedin = false;
		public $user_id, $patient_id, $doctor_id;
		public $message;

		function __construct() {
			// session_start();
			$this->check_message();
			$this->check_login();
		}

		public function is_loggedin(){
			return $this->loggedin;
		}

		public function patient_login($user){
			if ($user) {
				$this->patient_id = $_SESSION['patient'] = $user->patient_id;
				$this->loggedin = true;
			}
		}

		public function admin_login($user){
			if ($user) {
				$this->user_id = $_SESSION['admin'] = $user->user_id;
				$this->loggedin = true;
			}
		}

		public function doctor_login($user){
			if ($user) {
				$this->doctor_id = $_SESSION['doctor'] = $user->email;
				$this->loggedin = true;
			}
		}

		public function logout(){
			session_destroy();
			if (isset($_SESSION['patient'])) {
				unset($_SESSION['patient']);
				unset($this->patient_id);
				$this->loggedin = false;
			}
			else if (isset($_SESSION['admin'])) {
				unset($_SESSION['admin']);
				unset($this->user_id);
				$this->loggedin = false;
			}
			else if (isset($_SESSION['doctor'])) {
				unset($_SESSION['doctor']);
				unset($this->doctor_id);
				$this->loggedin = false;
			}
		}

		public function message($msg = ""){
			if (!empty($msg)) {
				// then this is "set message"
				// make sure you understand why $this->message=$msg wouldn't work
				$_SESSION['message'] = $msg;
			}
			else{
				// then this is "get message"
				return $this->message;
			}
		}

		private function check_login(){
			if (isset($_SESSION['patient'])) {
				$this->patient_id = $_SESSION['patient'];
				$this->loggedin = true;
			}
			else if (isset($_SESSION['admin'])) {
				$this->user_id = $_SESSION['admin'];
				$this->loggedin = true;
			}
			else if (isset($_SESSION['doctor'])) {
				$this->doctor_id = $_SESSION['doctor'];
				$this->loggedin = true;
			}
			else{
				unset($this->user_id);
				unset($this->doctor_id);
				unset($this->patient_id);
				$this->loggedin = false;
			}
		}

		private function check_message(){
			// Is there a message tored in the session
			if (isset($_SESSION['message'])) {
				// Add it as an attribute and erase the stored version
				$this->message = $_SESSION['message'];
				unset($_SESSION['message']);
			}
			else{
				$this->message = "";
			}
		}
	}

	$session = new Session();
	$message = $session->message();
?>