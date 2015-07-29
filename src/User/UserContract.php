<?php namespace Wasp\User;

use Wasp\ShieldWall\User\UserContractInterface;

/**
 * The user contract class
 *
 * @package Wasp
 * @subpackage User
 * @author Dan Cox
 */
class UserContract implements UserContractInterface
{
	/**
	 * The identifier
	 *
	 * @var String
	 */
	protected $identifier;

	/**
	 * The encrypted password
	 *
	 * @var String
	 */
	protected $password;

	/**
	 * Gets the value of Identifier
	 *
	 * @return String
	 */
	public function getIdentifier()
	{
		return $this->identifier;
	}

	/**
	 * Sets the value of Identifier
	 *
	 * @param String $Identifier
	 *
	 * @return UserContract
	 */
	public function setIdentifier($Identifier)
	{
		$this->identifier = $Identifier;
		return $this;
	}

	/**
	 * Gets the value of Password
	 *
	 * @return String
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * Sets the value of Password
	 *
	 * @param String $Password
	 *
	 * @return UserContract
	 */
	public function setPassword($Password)
	{
		$this->password = $Password;
		return $this;
	}

} // END class UserContract implements UserContractInterface

