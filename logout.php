<?php
	require_once("private/initialize.php");

	/*
	replacing

	// Do the logout processes and redirect to login page.
	after_successful_logout();
	//redirect_to('login.php');
	// header redirection often requires output buffering 
	// to be turned on in php.ini.
	header("Location: login.php");
	exit;
	
	with the actual code
	*/
	/*
	Useful php.ini file settings:
	session.cookie_lifetime = 0
	session.cookie_secure = 1
	session.cookie_httponly = 1
	session.use_only_cookies = 1
	session.entropy_file = "/dev/urandom"

	Must have already called:
	session_start();
	*/	
	// Actions to preform after every successful logout

	$_SESSION['logged_in'] = false;
	/*
	Use both for compatibility with all browsers
	and all versions of PHP.

	forcibly end the session
	*/
	session_unset();
	session_destroy();
	//redirect_to('login.php');
	// header redirection often requires output buffering 
	// to be turned on in php.ini.
	header("Location: login.php");
	exit;	
?>