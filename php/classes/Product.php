<?php
namespace mjordan30\public_html\datadesigned;
require_once("autoload.php");
/**
 * product the juice of Autographsy.com Users will be able to favorite all of their favorite sports memorabilia.
 * @author Michael Jordan <mikesjordan@gmail.com>
 * @version 4.2.0
 **/
class Product implements \JsonSerializable {
	/**
	 * id for this product; this is the primary key
	 * @var int $productId
	 */
	private $productId;
	/**
	 * description of product
	 * @var string $productDescription
	 */
	private $productDescription;
	/**
	 * price of the product
	 * @var string $productPrice
	 */
	private $productPrice;

	/**
	 * Product constructor.
	 * @param int|null $newProductId id of this product
	 * @param string $newProductDescription contains product description
	 * @param string $newProductPrice contains product price
	 * @throws \InvalidArgumentException if data is not valid
	 * @throws \RangeException if data is too long or negative
	 * @throws \TypeError if data out of bounds
	 * @throws \exception if another exception occurs
	 * @documentation php.net
	 */
	public function __construct(?int $newProductId, string $newProductDescription, string $newProductPrice) {
		try {
			$this->setProductId($newProductId);
			$this->setProductDescription($newProductDescription);
			$this->setProductPrice($newProductPrice);
		}
		// what exception is thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for productId
	 * @return int|null value of productId
	 */
	public function getProductId(): int {
		return($this->productId);
	}
	public function setProductId(?int $newProductId) : void {
		if($newProductId === null) {
			$this->productId = null;
			return;
		}
		if($newProductId <=0) {
			throw(new \RangeException("Product id is not positive"));
		}
		}

	/**
	 * accessor method for productDescription
	 *
	 * @return string value of productDescription
	 */
	public function getProductDescription():string {
		return $this->productDescription;
	}

	/**
	 * Mutator method for ProductDescription
	 * @param string $newProductDescription new value
	 * @throws \InvalidArgumentException if $newProductDescription is not a string or insecure
	 * @throws \RangeException if $newProductDescription is greater then 2k char
	 * @throws \TypeError if $newProductDescription is not a string
	 **/
	public function setProductDescription(string $newProductDescription) : void {
		$newProductDescription = trim($newProductDescription);
		$newProductDescription = filter_var($newProductDescription, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newProductDescription) === true) {
			throw(new \InvalidArgumentException("Product description is empty or insecure"));
		}
		if(strlen($newProductDescription) > 2000) {
			throw(new \RangeException("Product description exceeds 2000 char limit"));
		}
		$this->productDescription = $newProductDescription;
		}
	/**
	 * accessor method for productPrice
	 * @return string value of productPrice
	 **/
		public function getProductPrice() :string {
		return $this->productPrice;
		}
	/**
	 * Mutator method for productPrice
	 * @param string $newProductPrice value of
	 * @throws \InvalidArgumentException if #newProductPrice is not a string or insc
	 * @throws \RangeException if $newProductPrice is greater then 10
	 * @throws \TypeError if $newProductPrice is not a string
	 **/
		public function setProductPrice(string $newProductPrice) : void {
			$newProductPrice = trim($newProductPrice);
			$newProductPrice = filter_var($newProductPrice, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
			if(empty($newProductPrice) === true) {
				throw(new \InvalidArgumentException("product price is empty or insecure"));
			}
			if(strlen($newProductPrice) > 10 ) {
				throw(new \RangeException("Product price exceeds limit of 10 OVERPRICED"));
			}
			$this->productPrice = $newProductPrice;
		}
		public function insert(\PDO $pdo) : void {
			if($this->productId !== null) {
				throw(new \PDOException("not a new product"));
			}
			// dat query table tho
			$query ="INSERT INTO product(productDescription, productPrice) VALUES (:productDescription, :productPrice)";
			$statement = $pdo->prepare($query);
			// bind the vars to place holders
			$parameters = ["productDescription" => $this->productDescription, "productPrice" => $this->productPrice];
			$statement->execute($parameters);
			// updates null productId with what sql provides
			$this->productId = intval($pdo->lastInsertId());
		}

	/**
	 * deletes product from sql
	 *
	 * @param \PDO $pdo pdo tinder object for sql
	 * @throws \PDOException when sql errors occur
	 */
		public function delete(\PDO $pdo) : void {
			// enforce the product is not null
			if($this->productId === null) {
				throw(new \PDOException("unable to delete a product that does not exist"));
			}
			// query template
			$query = "DELETE FROM product WHERE productId = :productId";
			$statement = $pdo->prepare($query);
			// 50 shades of var to place to holder
			$parameters = ["productId" => $this->productId];
			$statement->execute($parameters);
		}

	/**
	 * updates product in sql
	 *
	 * @param \PDO $pdo connection object
	 * @throws \PDOException when sql errors
	 */
		public function update(\PDO $pdo) : void {
			// enforce the productId is not null
			if($this->productId === null) {
				throw(new \PDOException("unable to update a product that does not exist"));
			}
			// creates query table
			$query = "UPDATE product Set productDescription = :productDescription, productPrice = :productPrice WHERE productId = :productId";
			$statement = $pdo->prepare($query);
			// binds var to place holders
			$parameters = [ "productDescription" => $this->productDescription, "productPrice" => $this->productPrice, "productId" => $this->productId];
			$statement->execute($parameters);
		}
		public static function getProductByProductId(\PDO $pdo, int $productId) : ?Product {
			if($productId <= 0) {
				throw(new \PDOException("product id is not positive"));
			}
			$query = "SELECT productId, productDescription, productPrice FROM Product WHERE productId = :productId";
			$statement = $pdo->prepare($query);
			$parameters = ["productId" => $productId];
			$statement->execute($parameters);
			try {
				$product = null;
				$statement->setFetchMode(\PDO::FETCH_ASSOC);
				$row = $statement->fetch();
				if($row !== false) {
					$product = new Product($row["productId"], $row["productDescription"],$row ["productPrice"]);
				}
			} catch(\Exception $exception) {
				throw(new \PDOException($exception->getMessage(), 0 ,$exception));
			}
			return($product);
}
	/**
	 * get product by content
	 *
	 * @param \PDO $pdo connection object
	 * @param string $productDescription product description to search for
	 * @return \SPLFixedArray fixed array of products found
	 * @throws \PDOException when sql errors occur
	 */
		public static function getProductByProductDescription(\PDO $pdo, string $productDescription) : \SPLFixedArray {
			// sanitize the descriptions before searching
			$productDescription = trim($productDescription);
			$productDescription = filter_var($productDescription, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
			if(empty($productDescription) === true) {
				throw (new \PDOException("product content is invalid"));
			}
			// query table creator
			$query = "SELECT productId, productDescription, productPrice FROM product WHERE productDescription LIKE :productDescription";
			$statement = $pdo->prepare($query);
			//bind the product description to the place holders
			$productDescription = "%productDescription%";
			$parameters = ["productDescription" => $productDescription];
			$statement->execute($parameters);
			// array of products
			$products = new \SPLFixedArray($statement->rowCount());
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			while(($row = $statement->fetch()) !== false) {
				try {
					$product = new Product($row["productId"], $row["productDescription"], $row["productPrice"]);
					$products[$products->key()] = $product;
					$products->next();
				} catch(\Exception $exception) {
					// rethrows if row can't convert
					throw(new \PDOException($exception->getMessage(), 0, $exception));
				}
			}
			return($products);
		}
	/**
	 * formats vars for json
	 * @return array of $this
	 */
		public function jsonSerialize() {
			$fields = get_object_vars($this);
				return($fields);
		}
}
