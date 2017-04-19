<?php
namespace mjordan30\datadesigned;

/**
 * Not sure if this is the correct namespace dir
 * @author Michael Jordan <mikesjordan@gmail.com>
 * @version 4.2.0
 **/
class Profile {
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
	 * @return int for profile
	 **/
	public function getProfileId(): int {
		return ($this->profileId);
	}

	/**
	 * @param int|null $newProfileId
	 **/
	public function setProfileId($newProfileId) {
		$newProfileId = filter_var($newProfileId, FILTER_VALIDATE_INT);
		if($newProfileId === false) {
			throw(new \UnexpectedValueException("profile id is not a valid integer"));
		}
		$this->profileId = intval($newProfileId);
	}
	public function getProfileAtHandle() {
		return ($this->profileAtHandle);
	}
	public function setProfileAtHandle($newProfileAtHandle) {
		$newProfileAtHandle = filter_var($newProfileAtHandle, FILTER_VALIDATE_INT);
		if($newProfileAtHandle === false) {
			throw(new \UnexpectedValueException("at handle is not a valid integer"));
		}
		$this->profileAtHandle = intval($newProfileAtHandle);
	}
		/**
		 * @param null|string $newProfileActivationToken
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
	 * @return string
	 */
	public function getProfileEmail(): string {
		return $this->profileEmail;
	}
	/**
	 * @param string $newProfileEmail
	 **/
	public function setProfileEmail(string $newProfileEmail): void {
		$newProfileEmail = trim($newProfileEmail);
		$newProfileEmail = filter_var($newProfileEmail, FILTER_VALIDATE_EMAIL);
		if(empty($newProfileEmail) === true) {
			throw(new \InvalidArgumentException("profile email is empty or insecure"));
		}
		if(strlen($newProfileEmail) > 128){
			throw+(new \RangeException("Your Email is HUGELY in length"));
		}
		$this->profileEmail = $newProfileEmail;
	}
	/**
	 * @return null|string
	 **/
	public function getProfilePhone(): ?string {
			return ($this->profilePhone);
		}
		/**
		 * @param null|string $newProfilePhone
		 **/
public function setProfilePhone(?string $newProfilePhone): void {
		if($newProfilePhone === null){
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
		 * @return string
		 **/
		public function getProfileHash(): string {
	return $this->profileHash;
		}
		/**
		 * @param string $newProfileHash
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
		 * @return string
		 **/
			public function getProfileSalt(): string {
			return $this->profileSalt;
		}
		/**
		 * @param string $newProfileSalt
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

		}