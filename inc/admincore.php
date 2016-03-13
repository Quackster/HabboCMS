<?php

define('IN_HK', true);
define('HK_WWW', WWW . '/manage');

function fMessage($type, $message)
{
	if(isset($_SESSION['fmsg'])) {
		return;
	}

	$_SESSION['fmsg_type'] = $type;
	$_SESSION['fmsg'] = $message;
}

if(isset($_SESSION['HK_USER_N']) && isset($_SESSION['HK_USER_H'])) {
	$userN = $_SESSION['HK_USER_N'];
	$userH = $_SESSION['HK_USER_H'];
	
	if($users->validateUser($userN, $userH)) {
		define('HK_LOGGED_IN', true);
		define('HK_USER_NAME', $userN);
		define('HK_USER_ID', $users->name2id($userN));
		define('HK_USER_HASH', $userH);
	} else {
		@session_destroy();
		header('Location: /');
		exit;
	}	
} else {
	define('HK_LOGGED_IN', false);
	define('HK_USER_NAME', 'Guest');
	define('HK_USER_ID', -1);
	define('HK_USER_HASH', null);
}