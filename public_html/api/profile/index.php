<?php

/**
 * WHAT ARE THOSE
 **/
//require_once(dirname(__DIR__, 3) . "/vendor/autoload.php";
//require_once(dirname(__DIR__, 3) . "php/classes/autoload.php");
//require_once(dirname(__DIR__, 3) . "php/lib/xrsf.php");
//require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
use mjordan30\public_html\datadesigned\ {
	Profile
};

/**
 * api for profile
 *
 * @author george Gkephart
 */
// verify session is dank if not active start it
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
// prepares an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {
	// this would grab the mySQL connection
	// $pdo = connectToEncryptedMySql(".ini file goes here");
	// determine which http method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $S_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];
	// sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
	$profileAtHandle = filter_input(INPUT_GET, "profileAtHandle", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$profileEmail = filter_input(INPUT_GET, "profileEmail", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$profilePhone filter_input(INPUT_GET, "profilePhone", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	// make sure the id is valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true || $id < 0)) {
		throw(new InvalidArgumentException("id cannot be empty or negative", 405));
	}
	if($method === "GET") {
		// set XSRF cookie
		setXsrfCookie();

		if(empty($id) === false) {
			$profile = Profile::getProfileByProfileId($pdo, $id);
			if($profile !== null) {
				$reply->data = $profile;
			} else if(empty($profileAtHandle) === false) {
				$profile = Profile::getProfileByProfileAtHandle($pdo, $profileAtHandle);
				if($profile !== null) {
					$reply->data = $profile;
				}
			}

		}
	}
}
