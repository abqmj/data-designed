<?php
namespace mjordan30\public_html\datadesigned;
require_once("autoload.php");
/**
 * product the juice of Autographsy.com Users will be able to favorite all of their favorite sports memorabilia.
 * @author Michael Jordan <mikesjordan@gmail.com>
 * @version 4.2.0
 **/
class Product {
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

	}
