<?php
namespace mjordan30\datadesigned;
/**
 *Favorite is a table displaying users favorite memorabilia.
 * @author Michael Jordan <mikesjordan@gmail.com>
 * version 4.2.0
 **/

class Favorite {
	/** use ValidateDate; I dont have dates in my conceptual model **/
	/** id for favorite product primary key **/
	private $FavoriteProductId;
	/**
	 *profile id favorites product
	 * @var int $favoriteProfileId
	 **/
	private $FavoriteProfileId;
	/**
	 * constructor for favorite
	 **/
	public function __construct(int $newFavoriteProfileId, int $newFavoriteProductId){
		try {
			$this->setFavoriteProfileId($newFavoriteProfileId);
			$this->setFavoriteProductId($newFavoriteProductId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception){
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * @return int of profile
	 **/
	public function getFavoriteProfileId() : int {
	return ($this->favoriteProfileId);
	}

	/**
	 * @param int $newProfileId
	 **/
	public function setFavoriteProfileId(int $newProfileId) : void {
		if($newProfileId <= 0) {
			throw(new \RangeException("profile id is not positive"));
		}
		$this->favoriteProfileId = $newProfileId;
		}

	/**
	 * @param int $newFavoriteProductId
	 **/
		public function setFavoriteProductId(int $newFavoriteProductId) : void {
		if($newFavoriteProductId <= 0){
			throw(new \RangeException("product id is not positive"));
		}
		$this->favoriteProductId = $newFavoriteProductId;
	}

}