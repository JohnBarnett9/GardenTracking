<?php 
//csrf_request_type_functions
// GET requests should not make changes
// Only POST requests should make changes
function request_is_get() {
	return $_SERVER['REQUEST_METHOD'] === 'GET';
}
//not used
function request_is_post() {
	return $_SERVER['REQUEST_METHOD'] === 'POST';
}
/*
Usage:
if(request_is_post()) {
	... process form, update database, etc.
} else {
	... do something safe, redirect, error page, etc.
}
*/


//csrf_token_functions
// Must call session_start() before this loads

// Generate a token for use with CSRF protection.
// Does not store the token.
function csrf_token() {
	return md5(uniqid(rand(), TRUE));
}

// Generate and store CSRF token in user session.
// Requires session to have been started already.
function create_csrf_token() {
	$token = csrf_token();
	$_SESSION['csrf_token'] = $token;
	$_SESSION['csrf_token_time'] = time();
	return $token;
}

// Destroys a token by removing it from the session.
function destroy_csrf_token() {
	$_SESSION['csrf_token'] = null;
	$_SESSION['csrf_token_time'] = null;
	return true;
}

// Return an HTML tag including the CSRF token 
// for use in a form.
// Usage: echo csrf_token_tag();
function csrf_token_tag() {
	$token = create_csrf_token();
	return "<input type=\"hidden\" name=\"csrf_token\" value=\"".$token."\">";
}

// Returns true if user-submitted POST token is
// identical to the previously stored SESSION token.
// Returns false otherwise.
function csrf_token_is_valid() {
	if(isset($_POST['csrf_token'])) {
		$user_token = $_POST['csrf_token'];
		$stored_token = $_SESSION['csrf_token'];
		return $user_token === $stored_token;
	} else {
		return false;
	}
}

// You can simply check the token validity and 
// handle the failure yourself, or you can use 
// this "stop-everything-on-failure" function. 
function die_on_csrf_token_failure() {
	if(!csrf_token_is_valid()) {
		die("CSRF token validation failed.");
	}
}

// Optional check to see if token is also recent
function csrf_token_is_recent() {
	$max_elapsed = 60 * 60 * 24; // 1 day
	if(isset($_SESSION['csrf_token_time'])) {
		$stored_time = $_SESSION['csrf_token_time'];
		return ($stored_time + $max_elapsed) >= time();
	} else {
		// Remove expired token
		destroy_csrf_token();
		return false;
	}
}


//general_functions
// Put all of your general functions in this file

// header redirection often requires output buffering 
// to be turned on in php.ini.
/* don't need this 
function redirect_to($new_location) {
  header("Location: " . $new_location);
  exit;
}
*/



//index.php 
//don't copy this code




//request_forgery_functions
// Use with request_is_post() to block posting from off-site forms
//not used
function request_is_same_domain() {
	if(!isset($_SERVER['HTTP_REFERER'])) {
		return false;// No refererer sent, so can't be same domain
	} else {
		$referer_host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
		$server_host = $_SERVER['HTTP_HOST'];

		/*Uncomment for debugging*/
		echo 'Request from: ' . $referer_host . "<br />";
		echo 'Request to: ' . $server_host . "<br />";

		return ($referer_host == $server_host) ? true : false;
	}
}
/*
Uncomment for testing
if(request_is_same_domain()) {
	echo 'Same domain. POST requests should be allowed.<br />';
} 
else {
	echo 'Different domain. POST requests should be forbidden.<br />';
}
echo '<br />';
echo '<a href="">Same domain link</a><br />';
*/



//session_hijacking_functions
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

// Function to forcibly end the session
function end_session() {
	/*
	Use both for compatibility with all browsers
	and all versions of PHP.
	*/
	session_unset();
	session_destroy();
}

// Does the request IP match the stored value?
function request_ip_matches_session() {
	// return false if either value is not set
	if(!isset($_SESSION['ip']) || !isset($_SERVER['REMOTE_ADDR'])) {
		return false;
	}
	if($_SESSION['ip'] === $_SERVER['REMOTE_ADDR']) {
		return true;
	} 
	else {
		return false;
	}
}

// Does the request user agent match the stored value?
function request_user_agent_matches_session() {
	// return false if either value is not set
	if(!isset($_SESSION['user_agent']) || !isset($_SERVER['HTTP_USER_AGENT'])) {
		return false;
	}
	if($_SESSION['user_agent'] === $_SERVER['HTTP_USER_AGENT']) {
		return true;
	} else {
		return false;
	}
}

// Has too much time passed since the last login?
function last_login_is_recent() {
	$max_elapsed = 60 * 60 * 24; // 1 day
	// return false if value is not set
	if(!isset($_SESSION['last_login'])) {
		return false;
	}
	if(($_SESSION['last_login'] + $max_elapsed) >= time()) {
		return true;
	} else {
		return false;
	}
}

// Should the session be considered valid?
/*
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
*/

// If session is not valid, end and redirect to login page.
function confirm_session_is_valid() {
	if(!is_session_valid()) {
		end_session();
		// Note that header redirection requires output buffering 
		// to be turned on or requires nothing has been output 
		// (not even whitespace).
		header("Location: login.php");
		exit;
	}
}


// Is user logged in already?
function is_logged_in() {
	return (isset($_SESSION['logged_in']) && $_SESSION['logged_in']);
}

// If user is not logged in, end and redirect to login page.
function confirm_user_logged_in() {
	if(!is_logged_in()) {
		end_session();
		// Note that header redirection requires output buffering 
		// to be turned on or requires nothing has been output 
		// (not even whitespace).
		header("Location: login.php");
		exit;
	}
}


// Actions to preform after every successful login
function after_successful_login() {
	/* 
	Regenerate session ID to invalidate the old one.
	Super important to prevent session hijacking/fixation.
	*/
	session_regenerate_id();

	$_SESSION['logged_in'] = true;

	// Save these values in the session, even when checks aren't enabled 
	$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
	$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
	$_SESSION['last_login'] = time();

}

// Actions to preform after every successful logout
function after_successful_logout() {
	$_SESSION['logged_in'] = false;
	end_session();
}

// Actions to preform before giving access to any 
// access-restricted page.
function before_every_protected_page() {
	confirm_user_logged_in();
	confirm_session_is_valid();
}

/*
Uncomment to demonstrate usage

if(isset($_GET['action'])) {
	if($_GET['action'] == "login") {
		after_successful_login();
	}
	if($_GET['action'] == "logout") {
		after_successful_logout();
	}
}

echo "Session ID: " . session_id() . "<br />";
echo "Logged in: " . (is_logged_in() ? 'true' : 'false') . "<br />";
echo "Session valid: " . (is_session_valid() ? 'true' : 'false') . "<br />";
echo "<br />";
echo "--- SESSION ---<br />";
var_dump($_SESSION);
echo "--------------------<br />";
echo "<br />";

echo "<a href=\"?action=new_page\">Simulate a new page request</a><br />";
echo "<a href=\"?action=login\">Simulate a log in</a><br />";
echo "<a href=\"?action=logout\">Simulate a log out</a>";
*/

//sqli_escape_functions
// Escapes a string to render it safe for SQL.
// Assumes your database connection is assigned to $database.
// Modify this if you use something else ($db, $sqli, $mysql, etc.).
function sql_prep($string) {
	global $database;
	if($database) {
		return mysqli_real_escape_string($database, $string);
	} else {
		// addslashes is almost the same, but not quite as secure.
		// Fallback only when there is no database connection available.
	 	return addslashes($string);
	}
}
/*
Usage:
$sql_safe_username = sql_prep($_POST['username']);
*/


//validation_functions
/*
Core validation functions
These need to be called from another validation function which 
handles error reporting.

For example:

$errors = [];
function validate_presence_on($required_fields) {
  global $errors;
  foreach($required_fields as $field) {
    if (!has_presence($_POST[$field])) {
      $errors[$field] = "'" . $field . "' can't be blank";
    }
  }

}
*/

/*
* validate value has presence
use trim() so empty spaces don't count
use === to avoid false positives
empty() would consider "0" to be empty
*/
function has_presence($value) {
	$trimmed_value = trim($value);
	return isset($trimmed_value) && $trimmed_value !== "";
}

// * validate value has string length
// leading and trailing spaces will count
// options: exact, max, min
// has_length($first_name, ['exact' => 20])
// has_length($first_name, ['min' => 5, 'max' => 100])
function has_length($value, $options=[]) {
	if(isset($options['max']) && (strlen($value) > (int)$options['max'])) {
		return false;
	}
	if(isset($options['min']) && (strlen($value) < (int)$options['min'])) {
		return false;
	}
	if(isset($options['exact']) && (strlen($value) != (int)$options['exact'])) {
		return false;
	}
	return true;
}

// * validate value has a format matching a regular expression
// Be sure to use anchor expressions to match start and end of string.
// (Use \A and \Z, not ^ and $ which allow line returns.) 
// 
// Example:
// has_format_matching('1234', '/\d{4}/') is true
// has_format_matching('12345', '/\d{4}/') is also true
// has_format_matching('12345', '/\A\d{4}\Z/') is false
function has_format_matching($value, $regex='//') {
	return preg_match($regex, $value);
}

// * validate value is a number
// submitted values are strings, so use is_numeric instead of is_int
// options: max, min
// has_number($items_to_order, ['min' => 1, 'max' => 5])
function has_number($value, $options=[]) {
	if(!is_numeric($value)) {
		return false;
	}
	if(isset($options['max']) && ($value > (int)$options['max'])) {
		return false;
	}
	if(isset($options['min']) && ($value < (int)$options['min'])) {
		return false;
	}
	return true;
}

// * validate value is inclused in a set
function has_inclusion_in($value, $set=[]) {
  return in_array($value, $set);
}

// * validate value is excluded from a set
function has_exclusion_from($value, $set=[]) {
  return !in_array($value, $set);
}


/*
* validate uniqueness
A common validation, but not an easy one to write generically.
Requires going to the database to check if value is already present.
Implementation depends on your database set-up.
Instead, here is a mock-up of the concept.
Be sure to escape the user-provided value before sending it to the database.
Table and column will be provided by us and escaping them is optional.
Also consider whether you want to trim whitespace, or make the query 
case-sentitive or not.

function has_uniqueness($value, $table, $column) {
  $escaped_value = mysql_escape($value);
  sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = '{$escaped_value}';"
  if count > 0 then value is already present and not unique
}
*/

//xss_sanitation_functions
// Sanitize functions
// Make sanitizing easy and you will do it often

// Sanitize for HTML output 
function h($string) {
	return htmlspecialchars($string);
}

// Sanitize for JavaScript output
function j($string) {
	return json_encode($string);
}

// Sanitize for use in a URL
function u($string) {
	return urlencode($string);
}

/*
Usage examples, leave commented out
echo h("<h1>Test string</h1><br />");
echo j("'}; alert('Gotcha!'); //");
echo u("?title=Working? Or not?");
*/







?>