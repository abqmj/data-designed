<?php
/**
 * IDK
 **/
//require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
//require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
//require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
//require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
use mjordan30\public_html\datadesigned\{
	Product,
	// we only use profile class for testing purposes?
	Profile
};
// verify the session is lit
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
// prep an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {
	// get the mysql connection
	$pdo = connectioToEncryptedMySql("ini goes here");
	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];
	// sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
	$productProfileId = filter_input(INPUT_GET, "productProfileId", FILTER_VALIDATE_INT);
	$productDescription = filter_input(INPUT_GET, "productDescription", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	//make sure the id is valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true || $id < 0)) {
		throw(new InvalidArgumentException("id cannot be empty or negative", 405));
	}
	// handle GET request - if id is present, that product is returned, otherwise all products are returned
	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();
		//get a specific product or all products and update reply
		if(empty($id) === false) {
			$product = Product::getProductByProductId($pdo, $id);
			if($product !== null) {
				$reply->data = $product;
			}
		} else if(empty($productContent) === false) {
			$products = Product::getProductByProductContent($pdo, $productContent)->toArray();
			if($products !== null) {
				$reply->data = $products;
			}
		} else {
			$products = Product::getAllProducts($pdo)->toArray();
			if($products !== null) {
				$reply->data = $products;
			}
		}
	} else if($method === "PUT" || $method === "POST") {
		verifyXsrf();
		$requestContent = file_get_contents("php://input");
		// Retrieves the JSON package that the front end sent, and stores it in $requestContent. Here we are using file_get_contents("php://input") to get the request from the front end. file_get_contents() is a PHP function that reads a file into a string. The argument for the function, here, is "php://input". This is a read only stream that allows raw data to be read from the front end request which is, in this case, a JSON package.
		$requestObject = json_decode($requestContent);
		// This Line Then decodes the JSON package and stores that result in $requestObject
		//make sure product content is available (required field)
		if(empty($requestObject->productContent) === true) {
			throw(new \InvalidArgumentException ("No content for Product.", 405));
		}
		// make sure product date is accurate (optional field)
		if(empty($requestObject->productDate) === true) {
			$requestObject->productDate = null;
		}
		//  make sure profileId is available
		if(empty($requestObject->productProductId) === true) {
			throw(new \InvalidArgumentException ("No Profile ID.", 405));
		}
		//perform the actual put or post
		if($method === "PUT") {
			//enforce that the end user has a XSRF token.
			verifyXsrf();
			// retrieve the product to update
			$product = Product::getProductByProductId($pdo, $id);
			if($product === null) {
				throw(new RuntimeException("Product does not exist", 404));
			}
			//enforce the user is signed in and only trying to edit their own product
			if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId() !== $product->getProductProfileId()) {
				throw(new \InvalidArgumentException("You are not allowed to edit this product", 403));
			}
			// update all attributes
			$product->setProductDate($requestObject->productDate);
			$product->setProductContent($requestObject->productContent);
			$product->update($pdo);
			// update reply
			$reply->message = "Product updated OK";
		} else if($method === "POST") {
			//enforce that the end user has a XSRF token.
			verifyXsrf();
			// enforce the user is signed in
			if(empty($_SESSION["profile"]) === true) {
				throw(new \InvalidArgumentException("you must be logged in to post products", 403));
			}
			// create new product and insert into the database
			$product = new Product(null, $requestObject->productProfileId, $requestObject->productContent, null);
			$product->insert($pdo);
			// update reply
			$reply->message = "Product created OK";
		}
	} else if($method === "DELETE") {
		//enforce that the end user has a XSRF token.
		verifyXsrf();
		// retrieve the Product to be deleted
		$product = Product::getProductByProductId($pdo, $id);
		if($product === null) {
			throw(new RuntimeException("Product does not exist", 404));
		}
		//enforce the user is signed in and only trying to edit their own product
		if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId() !== $product->getProductProfileId()) {
			throw(new \InvalidArgumentException("You are not allowed to delete this product", 403));
		}
		// delete product
		$product->delete($pdo);
		// update reply
		$reply->message = "Product deleted OK";
	} else {
		throw (new InvalidArgumentException("Invalid HTTP method request"));
}
// update the $reply->status $reply->message
} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}
header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}
// encode and return reply to front end caller
echo json_encode($reply);
