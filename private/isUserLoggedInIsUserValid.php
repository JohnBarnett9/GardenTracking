<?php 

/*
this file is to replace these 2 functions
with the code that runs

To be private, page must require user to be 
considered logged in and session must be valid.
If not, these functions will redirect the user 
to login.php.
confirm_user_logged_in();
confirm_session_is_valid();
*/


/*
replaces confirm_user_logged_in();
my simplified version 
confirm user is logged in
If user is not logged in, end and redirect to login page.

To be private, page must require user to be 
considered logged in. 
If not, these functions will redirect the user 
to login.php.

Useful php.ini file settings:
session.cookie_lifetime = 0
session.cookie_secure = 1
session.cookie_httponly = 1
session.use_only_cookies = 1
session.entropy_file = "/dev/urandom"

Must have already called:
session_start();
*/
if(!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'])){
	/*
	Use both for compatibility with all browsers
	and all versions of PHP.
	*/
	session_unset();
	session_destroy();
	// Note that header redirection requires output buffering 
	// to be turned on or requires nothing has been output 
	// (not even whitespace).
	header("Location: login.php");
	exit;
}

/*
to be private
and session must be valid.
*/
//replaces confirm_session_is_valid();
// If session is not valid, end and redirect to login page.
if(!is_session_valid()) {
	/*
	Use both for compatibility with all browsers
	and all versions of PHP.
	*/
	session_unset();
	session_destroy();

	// Note that header redirection requires output buffering 
	// to be turned on or requires nothing has been output 
	// (not even whitespace).
	header("Location: login.php");
	exit;
}
	
// Should the session be considered valid?
function is_session_valid() {
	$check_ip = true;
	$check_user_agent = true;
	$check_last_login = true;

	if($check_ip && !request_ip_matches_session()) {
		return false;
	}
	if($check_user_agent && !request_user_agent_matches_session()) {
		return false;
	}
	if($check_last_login && !last_login_is_recent()) {
		return false;
	}
	return true;
}
?>