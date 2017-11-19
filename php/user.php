<?php

require_once(BASE_PATH . "triton/client.php");

class User
{
	public static function login($username, $password)
	{

		session_start();

		$_SESSION['id'] = $user[0]['id'];

		return array('success'=>true);
	}

	public static function logout()
	{
		session_start();
		session_unset();
		session_destroy();
		return array('success'=>true);
	}
}

?>
