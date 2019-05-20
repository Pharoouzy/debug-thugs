<?php 
	
	// Defining the core paths
	defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
	defined('SITE_ROOT') ? null : define('SITE_ROOT', 'C:'.DS.'server'.DS.'htdocs'.DS.'telemedicine');
	defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'libraries'.DS);
	defined('CLS_PATH') ? null : define('CLS_PATH', SITE_ROOT.DS.'classes'.DS);
	
	// order is important
	require_once(LIB_PATH.'config.php');

	// loading basic functions next so that everything after can use them
	require_once(LIB_PATH.'functions.php');

	// loading core objects
	require_once(CLS_PATH.'Session.php');
	require_once(CLS_PATH.'Database.php');
	require_once(CLS_PATH.'DatabaseObject.php');
	require_once CLS_PATH.'Pagination.php';

	// loading database-related classes
	require_once CLS_PATH.'Login.php';
	require_once CLS_PATH.'Administrator.php';
	require_once CLS_PATH.'Doctor.php';
	require_once CLS_PATH.'Patient.php';
	require_once CLS_PATH.'Department.php';
	require_once CLS_PATH.'Conversation.php';

?>