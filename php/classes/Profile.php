<?php
namespace mjordan30\public_html\datadesigned;

/**
 * Not sure if this is the correct namespace dir
 * @author Michael Jordan <mikesjordan@gmail.com>
 * @version 4.2.0
 **/
class Profile implements \JsonSerializable {
	/**
	 * this is the primary key for the profile
	 * @var int $profileId
	 **/
	private $profileId;
	/**
	 * token verifies profile is legit and not malicious
	 * @var string #profileActivationToken
	 **/
	private $profileActivationToken;
	/**
	 * this is a unique index for usernames or AtHandles
	 * @var string $profileAtHandle
	 **/
	private $profileAtHandle;
	/**
	 * index for email
	 * @var string $profileEmail
	 **/
	private $profileEmail;
	/**
	 * index for phone
	 * @var $profilePhone
	 **/
	private $profilePhone;
	/**
	 * hash profile password
	 * @var $profileHash
	 **/
	private $profileHash;
	/**
	 *salt for password
	 * @var $profileSalt
	 **/
	private $profileSalt;

	/**
	 * Constructor for this class
	 *
	 * @param int|null $newProfileId of this profile or null if new
	 * @param string $newProfileActivationToken token to safe guard
	 * @param string $newProfileAtHandle contains new handle
	 * @param string $newProfileEmail =email
	 * @param string $newProfilePhone =phone
	 * @param string $newProfileHash hash for pw
	 * @param string $newProfileSalt salt for pw
	 * @throws \InvalidArgumentException if data not valid
	 * @throws \RangeException if data too long or negative int
	 * @throws \TypeError if data outta bounds
	 * @throws \Exception if a different exception occurs
	 * @Documentation php.net
	 **/
	public function __construct(?int $newProfileId, ?string $newProfileActivationToken, ?string $newProfileAtHandle, string $newProfileEmail, ?string $newProfilePhone, string $newProfileHash, string $newProfileSalt) {
		try {
			$this->setProfileId($newProfileId);
			$this->setProfileActivationToken($newProfileActivationToken);
			$this->setProfileAtHandle($newProfileAtHandle);
			$this->setProfileEmail($newProfileEmail);
			$this->setProfilePhone($newProfilePhone);
			$this->setProfileHash($newProfileHash);
			$this->setProfileSalt($newProfileSalt);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			//determine the exception
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * This is the accessor method for ProfileId
	 * returns value for int of profile id (or naaaaaaah if new profile)
	 **/
	public function getProfileId(): int {
		return ($this->profileId);
	}

	/**
	 * This is the mutator method for ProfileId (where entities become X-Men)
	 *
	 * @param documents /the value int|null for $newProfileId of profileId
	 * @throws \RangeException if $newProfileId is not positive
	 * @throws \TypeError if $newProfileId is not an int
	 **/
	public function setProfileId($newProfileId) {
		$newProfileId = filter_var($newProfileId, FILTER_VALIDATE_INT);
		if($newProfileId === false) {
			throw(new \UnexpectedValueException("profile id is not a valid integer"));
		}
		$this->profileId = intval($newProfileId);
	}

	/**
	 * accessor method for profileAtHandle
	 *
	 * @return string value of getProfileAtHandle
	 */
	public function getProfileAtHandle() {
		return ($this->profileAtHandle);
	}

	/**
	 * mutator method for profileAtHandle
	 *
	 * @param string $newProfileAtHandle new value of profileAtHandle
	 * @throws \InvalidArgumentException if $newProfileAtHandle is not a string
	 * @throws \RangeException if $newProfileAtHandle is > 32 chars
	 * @throws \TypeError if $newProfileAtHandle is not a string
	 **/
	public function setProfileAtHandle($newProfileAtHandle) {
		$newProfileAtHandle = filter_var($newProfileAtHandle, FILTER_VALIDATE_INT);
		if($newProfileAtHandle === false) {
			throw(new \UnexpectedValueException("at handle is not a valid integer"));
		}
		$this->profileAtHandle = intval($newProfileAtHandle);
	}

	/**
	 * accessor method for ProfileActivationToken
	 *
	 * @return string value of the ProfileActivationToken
	 */
	public function getProfileActivationToken(): ?string {
		return ($this->profileActivationToken);
	}

	/**
	 * mutator method for ProfileActivationToken
	 *
	 * @param string $newProfileActivationToken
	 * @throws \InvalidArgumentException if the token is not a string or insecure
	 * @throws \RangeException if the token is not exactly 32 chars
	 * @throws \TypeError if the newProfileActivationToken is not a string
	 **/
	public function setProfileActivationToken(?string $newProfileActivationToken): void {
		if($newProfileActivationToken === null) {
			$this->profileActivationToken = null;
			return;
		}
		$newProfileActivationToken = strtolower(trim($newProfileActivationToken));
		if(ctype_xdigit($newProfileActivationToken) === false) {
			throw(new\RangeException("user activation is not valid"));
		}
		if(strlen($newProfileActivationToken) !== 32) {
			throw(new\RangeException("user activation token has to be 32"));
		}
		$this->profileActivationToken = $newProfileActivationToken;
	}

	/**
	 * accessor method for email
	 *
	 * @return string value of email
	 **/
	public function getProfileEmail(): string {
		return $this->profileEmail;
	}

	/**
	 * mutator method for email
	 *
	 * @param string $newProfileEmail new value of email
	 * @throws \InvalidArgumentException if $newEmail is not a valid email or insecure
	 * @throws \RangeException if $newEmail is > 128 characters
	 * @throws \TypeError if $newEmail is not a string
	 **/
	public function setProfileEmail(string $newProfileEmail): void {
		$newProfileEmail = trim($newProfileEmail);
		$newProfileEmail = filter_var($newProfileEmail, FILTER_VALIDATE_EMAIL);
		if(empty($newProfileEmail) === true) {
			throw(new \InvalidArgumentException("profile email is empty or insecure"));
		}
		if(strlen($newProfileEmail) > 128) {
			throw+(new \RangeException("Your Email is HUGELY in length"));
		}
		$this->profileEmail = $newProfileEmail;
	}

	/**
	 * accessor method for phone
	 *
	 * @return string value of phone or null
	 **/
	public function getProfilePhone(): ?string {
		return ($this->profilePhone);
	}

	/**
	 * mutator method for phone
	 *
	 * @param string $newProfilePhone new value of profilePhone
	 * @throws \InvalidArgumentException if $newPhone is not a string or insecure
	 * @throws \RangeException if $newPhone is > 32 characters
	 * @throws \TypeError if $newPhone is not a string
	 **/
	public function setProfilePhone(?string $newProfilePhone): void {
		if($newProfilePhone === null) {
			$this->profilePhone = null;
			return;
		}
		$newProfilePhone = trim($newProfilePhone);
		$newProfilePhone = filter_var($newProfilePhone, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newProfilePhone) === true) {
			throw(new \InvalidArgumentException("profile phone is empty or insecure"));
		}
		if(strlen($newProfilePhone) > 32) {
			throw(new \RangeException("Phone number is too long"));
		}
		$this->profilePhone = $newProfilePhone;
	}

	/**
	 * accessor method for profileHash
	 * @return string value of ProfileHash
	 **/
	public function getProfileHash(): string {
		return $this->profileHash;
	}

	/**
	 * mutator method for profile hash password
	 *
	 * @param string $newProfileHash
	 * @throws \InvalidArgumentException if the hash is not secure
	 * @throws \RangeException if the hash is not 128 characters
	 * @throws \TypeError if profile hash is not a string
	 **/
	public function setProfileHash(string $newProfileHash): void {
		$newProfileHash = trim($newProfileHash);
		$newProfileHash = strtolower($newProfileHash);
		if(empty($newProfileHash) === true) {
			throw(new \InvalidArgumentException("profile pw hash empty or insecure"));
		}
		if(!ctype_xdigit($newProfileHash)) {
			throw(new \InvalidArgumentException("profile pw hash is empty or insecure"));
		}
		if(strlen($newProfileHash) !== 128) {
			throw(new \RangeException("profile hash must be 128 chars"));
		}
		$this->profileHash = $newProfileHash;
	}

	/**
	 * accessor method for ProfileSalt
	 * @return string representation of the salt hex
	 **/
	public function getProfileSalt(): string {
		return $this->profileSalt;
	}

	/**
	 * mutator method for profile salt
	 *
	 * @param string $newProfileSalt
	 * @throws \InvalidArgumentException if the salt is not secure
	 * @throws \RangeException if the salt is not 64 characters
	 * @throws \TypeError if profile salt is not a string
	 **/
	public function setProfileSalt(string $newProfileSalt): void {
		$newProfileSalt = trim($newProfileSalt);
		$newProfileSalt = strtolower($newProfileSalt);
		if(!ctype_xdigit($newProfileSalt)) {
			throw(new \InvalidArgumentException("profile pw salt is empty or insecure"));
		}
		if(strlen($newProfileSalt) !== 64) {
			throw(new \RangeException("profile salt must be 128 chars"));
		}
		$this->profileSalt = $newProfileSalt;
	}

	/**
	 * inserting profile into mySQL via PDO
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL errors
	 * @throws \TypeError if $pdo is not a pdo connection object
	 * no TypeError throw in dylans example on github??
	 */
	public function insert(\PDO $pdo): void {
		// enforces profile id null (wont insert a profile if profile already exists)
		if($this->profileId !== null) {
			throw(new \PDOException("not a new profile"));
		}
		// creates query template
		$query = "INSERT INTO profile(profileActivationToken, profileAtHandle, profileEmail, profilePhone, profileHash, profileSalt) VALUES (:profileActivationToken, :profileAtHandle, :profileEmail, :profilePhone, :profileHash, :profileSalt)";
		$statement = $pdo->prepare($query);
		// binds the member vars to the place holders in the template
		$parameters = ["profileActivationToken" => $this->profileActivationToken, "profileAtHandle" => $this->profileAtHandle, "profileEmail" => $this->profileEmail, "profilePhone" => $this->profilePhone, "profileHash" => $this->profileHash, "profileSalt" => $this->profileSalt];
		$statement->execute($parameters);
		// updates null profileId with what mySQL provided
		$this->profileId = intval($pdo->lastInsertId());
	}

	/**
	 * deletes profile from sql
	 *
	 * @param \PDO $pdo connection object
	 * @throws \PDOException when sql errors occur
	 */
	public function delete(\PDO $pdo): void {
		// enforces if profileId is not null
		if($this->profileId === null) {
			throw (new \PDOException("unable to delete a profile that does not exist"));
		}
		// creates query template
		$query = "DELETE FROM profile WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);
		// bind the member vars to the place holders
		$parameters = ["profileId" => $this->profileId];
		$statement->execute($parameters);
	}

	/**
	 * updates profile from sql
	 * @param \PDO $pdo connection object
	 * @throws \PDOException when sql errors happen
	 */
	public function update(\PDO $pdo): void {
		if($this->profileId === null) {
			throw(new \PDOException("unable to delete a profile that does not exist"));
			}
// query table
$query = "UPDATE profile SET profileActivationToken = :profileActivationToken, profileAtHandle = :profileAtHandle, profileEmail = :profileEmail, profilePhone = :profilePhone, profileHash = :profileHash, profileSalt = :profileSalt WHERE profileId = :profileId";
$statement = $pdo->prepare($query);
// bind vars to place holders
$parameters = ["profileId" => $this->profileId, "profileActivationToken" => $this->profileActivationToken, "profileAtHandle" => $this->profileAtHandle, "profileEmail" => $this->profileEmail, "profilePhone" => $this->profilePhone, "profileHash" => $this->profileHash, "profileSalt" => $this->profileSalt];
$statement->execute($parameters);
		}

	/**
	 * GET SOME profile by profileId
	 *
	 * @param \PDO $pdo connection object
	 * @param int $profileId profileId to search for
	 * @return Profile|null profile or naaaaaah if not found
	 * @throws \PDOException when sql errors
	 */
		public static function getProfileByProfileId(\PDO $pdo, int $profileId): ?Profile {
		//sanitize profile id before searching
			if($profileId <= 0) {
			throw(new \PDOException("profile id is not positive"));
		}
		// create query template
		$query = "SELECT profileId, profileActivationToken, profileAtHandle, profileEmail, profilePhone, profileHash, profileSalt FROM profile WHERE profileId = :profileId";
		$statement = $pdo->prepare($query);
		// bind the profile id to place holders
			$parameters =["profileId" => $profileId];
			$statement->execute($parameters);
		try {
			$profile = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$profile = new Profile($row["profileId"], $row["profileActivationToken"], $row["profileAtHandle"], $row["profileEmail"], $row["profilePhone"], $row["profileHash"], $row["profileSalt"]);
			}
		} catch(\Exception $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($profile);
}
public static function getProfileByProfileAtHandle(\PDO $pdo, string $profileAtHandle) : \SPLFixedArray {
			//sanitize the at handle before searching
	$profileAtHandle = trim($profileAtHandle);
	$profileAtHandle = filter_var($profileAtHandle, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	if(empty($profileAtHandle) === true) {
		throw(new \PDOException("not a valid at handle"));
	}
// creates query table
	$query = "SELECT profileId, profileActivationToken, profileAtHandle, profileEmail, profilePhone, profileHash, profileSalt FROM profile Where profileAtHandle = :profileAtHandle";
	$statement = $pdo->prepare($query);
	// binds the at handle to place holder
	$parameters = ["profileAtHandle" => $profileAtHandle];
	$statement->execute("$parameters");

	$profiles = new \SPLFixedArray($statement->rowCount());
	$statement->setFetchMode(\PDO::FETCH_ASSOC);

	while (($row = $statement->fetch()) !== false) {
		try {
			$profile = new Profile($row["profileId"], $row["profileActivationToken"], $row["profileAtHandle"], $row["profileEmail"], $row["profileHash"], $row["profilePhone"], $row["profileSalt"]);
			$profiles[$profiles->key()] = $profile;
			$profiles->next();
		} catch(\Exception $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
	}
	return ($profiles);
}
	/**
	 * I think this is json
	 * @return array resulting state variables to serialize
	 */
				public function jsonSerialize() {
					return (get_object_vars($this));
				}
}