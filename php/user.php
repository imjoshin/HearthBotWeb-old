<?php

class User
{
	public static function login($username, $password)
	{
		$users = dbQuery("SELECT * FROM hearthuser WHERE username = ? AND password = MD5(?)", [$username, $password]);

		if (!is_array($users) || empty($users))
		{
			return array('success'=>false, 'output'=>'Invalid login.');
		}

		session_start();
		$_SESSION['id'] = $users[0]['id'];

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
