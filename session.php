<?php

class loginInfo {
	function __construct(){
		session_start();
		if (array_key_exists('ip', $_SESSION)
			&& array_key_exists('browseragent', $_SESSION)
			&& array_key_exists('UID', $_COOKIE)
			&& array_key_exists('uid', $_SESSION)
			&& $_SESSION['ip'] == $_SERVER['REMOTE_ADDR']
			&& $_SESSION['browseragent'] == $_SERVER['HTTP_USER_AGENT']
			&& $_SEESION['id'] == $_COOKIE['id']
			){

		} else {

		}
	}
}


?>