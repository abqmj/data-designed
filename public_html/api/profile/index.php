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
 * @inspiration Gkephart
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
	 $pdo = connectToEncryptedMySql(".ini file goes here");
	// determine which http method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $S_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];
	// sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
	$profileAtHandle = filter_input(INPUT_GET, "profileAtHandle", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

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

		} elseif($method === "PUT") {
			// enforce the user is signed in and only trying to edit their own profile
			if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId() !== $id) {
				throw(new \InvalidArgumentException("You are not allowed access to this profile", 403));
			}
			// decode the response from the front end
			$requestContent = file_get_contents("php://input");
			$requestObject = json_decode($requestContent);
			// retrieve the profile to be update
			$profile = Profile::getProfileByProfileId($pdo, $id);
			if($profile === null) {
				throw(new RuntimeException("profile does not exist", 404));
			}
			if(empty($requestObject->newPassword) === true) {
				// enforce that XSRF is present in the header
				verifyXsrf();
				// profile at handle exist
				if(empty($requestObject->profileAtHandle) === true) {
					throw(new \InvalidArgumentException("no profile at handle", 405));
				}
				// profile email is required?
				if(empty($requestObject->profileEmail) === true) {
					throw(new \InvalidArgumentException("no profile email present", 405));
				}
				if(empty($requestObject->profilePhone) === true) {
					$requestObject->ProfilePhone = $profile->getProfilePhone();
				}
				$profile->setProfileAtHandle($requestObject->profileAtHandle);
				$profile->setProfileEmail($requestObject->profileEmail);
				$profile->setProfilePhone($requestObject->profilePhone);
				$profile->update($pdo);
				// update reply
				$reply->message = "Profile Info Updated";
			}
			// enforces that currentpw newpw and confirm pw is present
			if(empty($requestObject->ProfilePassword) === false && empty($requestObject->profileConfirmPassword) === false && empty($requestContent->Confirmpassword) === false) {
				// make sure the new pw and confirm pw exist
				if($requestObject->newProfilePassword !== $requestObject->profileConfirmPassword) {
					throw(new RuntimeException("NEW PASSWORDS DO NOT MATCH", 401));
				}
				// hash the previous pw
				$currentPasswordHash = hash_pbkdf2("sha512", $requestObject->currentProfilePassword, $profile->getProfileSalt(), 262144);
				// make sure the hash given by the end user matches what is in the db
				if($currentPasswordHash !== $profile->getProfileHash()) {
					throw(new \RuntimeException("Old password is incorrect", 401));
				}
				//salt and hash the new password and update the profile object
				$newPasswordSalt = bin2hex(random_bytes(16));
				$newPasswordHash = hash_pbkdf2("sha512", $requestObject->newProfilePassword, $newPasswordSalt, 262144);
				$profile->setProfileHash($newPasswordHash);
				$profile->setProfileSalt($newPasswordSalt);
			}
			// preform the update to the db and update the message
			$profile->update($pdo);
			$reply->message = "profile password sucessfully update";
		} elseif($method === "DELETE") {
			// verify the XSRF Token
			$profile = Profile::getProfileByProfileId($pdo, $id);
			if($profile === null) {
				throw (new RuntimeException("profile does not exist"));
			}
			// enforce the users is signed in and only trying to edit their own profile
			if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId() !== $profile->getProfileId()) {
				throw(new InvalidArgumentException("Invalid HTTP request", 400));
			}
			$profile->delete($pdo);
			$reply->message = "Profile Deleted";
		} else {
			throw (new InvalidArgumentException("Invalid HTTP request", 400));
		}
	} catch(\Exception | \TypeError $exception) {
			$reply->status = $exception->getCode();
			$reply->message = $exception->getMessage();
		}
	header("Content-type: application/json");
	if($reply->data = null) {
		unset($reply->data);
	}
	// encode and return reply to front end caller
	echo json_encode($reply);

