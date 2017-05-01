<?php
/**
 * IDK
 **/
//require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
//require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
//require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
//require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
use mjordan30\public_html\datadesigned\ {
	Profile,
	Favorite
};
/**
 * API for favorite
 *
 * @inspiration George Kephart
 **/
// verify the session start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
// prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {
	$pdo = connectToEncryptedMySQL("RAWR.INI");
	// mock logged in user
	// this is only for testing purposes
	$_SESSION["profile"] = Profile::getProfileByProfileId($pdo, 732);
	// determine the search para
	$favoriteProfileId = filter_input(INPUT_GET, "FavoriteProfileId", FILTER_VALIDATE_INT);
	$favoriteProductId = filter_input(INPUT_GET, "FavoriteProfileId", FILTER_VALIDATE_INT);
	var_dump($favoriteProfileId);
	var_dump($favoriteProductId);
	if($method === "GET") {
		// set XSRF COOOKIE
		setXsrfCookie();
		// gets a specific favorite associated based on its composite key
		if ($favoriteProfileId !== null && $favoriteProductId !== null) {
			$favorite = Favorite::getFavoriteByFavoriteProductIdAndFavoriteProfileId($pdo, $favoriteProfileId, $favoriteProductId);
			if($favorite!==null) {
				$reply->data = $favorite;
			}
			// if none of the search parameters are met throw an exception
		} else If(empty($favoriteProfileId) === false) {
			$favorite = Favorite::getFavoriteByFavoriteProfileId($pdo, $favoriteProfileId)->toArray();
			if($favorite !== null) {
				$reply->data = $favorite;

			}
			// get all the favorites associated with the product Id
		} else if(empty($favoriteProductId) === false) {
			$favorite = Favorite::getFavoriteByFavoriteProductId($pdo, $favoriteProductId)->toArray();
			if($favorite !== null) {
				$reply->data = $favorite;
			}
		} else {
			throw new InvalidArgumentException("incorrect search parameters", 404);
		}
	} else if($method === "POST" || $method === "PUT") {
		//decode the response from the front end
		$requestContent = file_get_contents("php://input");
		$requestObject =json_decode($requestContent);
		if(empty($requestObject->favoriteProfileId) === true) {
			throw (new \InvalidArgumentException("No profile linked to the favorite", 405));
		}
		if(empty($requestObject->favoriteProductId) === true) {
			throw (new \InvalidArgumentException("no profile linked to the favorite", 405));
		}
		if($method === "POST") {
			//enforce users is signed in
			if(empty($_SESSION["profile"]) === true) {
				throw(new \InvalidArgumentException("YOU SHALL NOT PASS IF NOT LOGGED IN", 403));
			}
			$favorite = new Favorite($requestObject->favoriteProfileId, $requestObject->favoriteProductId);
			$favorite->insert($pdo);
			$reply->message = "favorite product GREAT SUCCESS!";
		} else if($method === "PUT") {
			// ENFORCE THY XSRF TOKEN
			verifyXsrf();
			// grab the favorite by its P.. composite key
			$favorite = Favorite::getFavoriteByFavoriteProductIdAndFavoriteProfileId($pdo, $requestObject->favoriteProfileId, $requestObject->favoriteProductId);
			if($favorite === null) {
				throw (new RuntimeException("favorite does not exist"));
			}
			if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId() !== $favorite->getFavoriteProfileId()){
				throw(new \InvalidArgumentException("You are not allowed to delete this product", 403));
			}
			$favorite->delete($pdo);
			$reply->message = "favorite deleted with GREAT SUCCESS!";
		}
	} else {
		throw new \InvalidArgumentException("invalid http request", 404);
	}
} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}
header("Content-Type: application/json");
if($reply->data === null) {
	unset($reply->data);
}
echo json_encode($reply);