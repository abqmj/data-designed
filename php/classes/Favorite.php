<?php
namespace mjordan30\public_html\datadesigned;
require_once("autoload.php");
/**
 *Favorite is a table displaying users favorite memorabilia.
 * @author Michael Jordan <mikesjordan@gmail.com>
 * version 4.2.0
 **/

class Favorite implements \JsonSerializable {
	/** use ValidateDate; I dont have dates in my conceptual model **/
	/** id for favorite product primary key **/
	private $favoriteProductId;
	/**
	 *profile id favorites product
	 * @var int $favoriteProfileId
	 **/
	private $favoriteProfileId;
	/**
	 * constructor for favorite
	 *
	 * @param int $newFavoriteProductId id of the parent profile
	 * @param int $newFavoriteProfileId id of the parent product
	 * @throws \InvalidArgumentException if data is not valid
	 * @throws \RangeException if data is too long or negative
	 * @throws \Exception if the obvious occurs
	 * @throws \TypeError if data types violate type hints?
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

	/**
	 * inserts the favorite into sql
	 *
	 * @param \PDO $pdo connection object
	 * @throws \PDOException when sql throws error
	 **/
	public function insert(\PDO $pdo) : void {
		// checks if object exist before inserting
			if($this->favoriteProfileId === null || $this->favoriteProductId === null) {
				throw(new \PDOException("not a valid favorite"));
				}
				// query template created
				$query = "INSERT INTO favorite(favoriteProfileId,favoriteProductId) VALUES(:favoriteProfileId, favoriteProductId)";
			$statement = $pdo->prepare($query);
			// bind the vars to the place holders
			$parameters = ["favoriteProfileId" => $this->favoriteProfileId, "favoriteProductId" => $this->favoriteProductId];
			$statement->execute($parameters);
}

	/**
	 * deletes favorite from sql
	 *
	 * @param \PDO $pdo connection object
	 * @throws \PDOException when sql errors occur
	 */
public function delete(\PDO $pdo) : void {
		if($this->favoriteProfileId === null || $this->favoriteProductId === null) {
			// ensure object exist
			throw(new \PDOException("not a valid favorite"));
		}
		// creates query table
		$query = "DELETE FROM favorite WHERE favoriteProfileId = :favoriteProfileId AND favoriteProductId = :favoriteProductId";
		$statement = $pdo->prepare($query);
		// michael grey the vars to the place holders
		$parameters = ["favoriteProfileId" => $this->favoriteProfileId, "favoriteProductId" => $this->favoriteProductId];
		$statement->execute($parameters);
	}
	public static function getFavoriteByFavoriteProductIdAndFavoriteProfileId(\PDO $pdo, int $favoriteProfileId, int $favoriteProductId) : ?Favorite {
	if($favoriteProfileId <= 0) {
		throw(new \PDOException("profile id is not positive"));
	}
		if($favoriteProductId <= 0){
			throw(new \PDOException("product Id is not valid"));
		}
		$query = "SELECT favoriteProfileId, favoriteProductId FROM favorite WHERE favoriteProfileId = :favoriteProfileId AND favoriteProductId = :favoriteProductId";
		$statement = $pdo->prepare($query);
		$parameters = ["favoriteProfileId" => $favoriteProfileId, "favoriteProductId" => $favoriteProductId];
		$statement->execute($parameters);
		try {
			$favorite = null;
				$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false){
				$favorite = new Favorite($row["favoriteProfileId"], $row["favoriteProductID"]);
			}
		} catch(\Exception $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($favorite);
	}
	/**
	 * not 100% if this is correct need to read more documentation just getting it outta the way
	 * @return array of variables $this
	 **/
	public function jsonSerialize() {
		$fields = get_object_vars($this);
		return ($fields);
	}
}