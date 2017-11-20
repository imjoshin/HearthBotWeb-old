<?php

require_once('user.php');
require_once('auth.php');

if (!isset($_POST['call']))
{
	return;
}

session_start();

if (!isset($_SESSION['id']) && !in_array(strtolower($_POST['call']), array("login", "logout")))
{
	$ret = array("success"=>false, "output"=>array(
		"message"=>"Session expired. Please refresh."
	));
	return json_encode($ret);
}

// look for serialized form
if (isset($_POST['form']))
{
	$_POST = array_merge($_POST, unserializeForm($_POST['form']));
	unset($_POST['form']);
}

switch(strtolower($_POST['call']))
{
	case 'login':
		$ret = User::login($_POST['username'], $_POST['password']);
		break;
	case 'logout':
		$ret = User::logout();
		break;
	case 'save_settings':
		$ret = Game::saveSettings($_POST);
		break;
}

echo json_encode($ret);

function unserializeForm($array)
{
	$data = array();
	foreach(explode('&', $array) as $value)
	{
		$value1 = explode('=', $value);
		$key = urldecode($value1[0]);
		$value = urldecode($value1[1]);
		if (preg_match('/\[.+\]/', $key, $match))
		{
			$arrayName = str_replace($match[0], '', $key);
			$arrayKey = str_replace(array('[', ']'), '', $match[0]);

			if (!isset($data[$arrayName]))
			{
				$data[$arrayName] = array();
			}

			$data[$arrayName][$arrayKey] = $value;
		}
		else
		{
			$data[$key] = $value;
		}
	}

	return $data;
}
?>
