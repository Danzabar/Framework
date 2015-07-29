<?php namespace Wasp\Test\ShieldWall\Extension;

use Wasp\ShieldWall\Courier\CourierInterface,
	Wasp\ShieldWall\User\UserContractInterface,
	Wasp\Utils\Collection;

/**
 * The test courier class
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class Courier implements CourierInterface
{

	/**
	 * A collection of users, for test purposes
	 *
	 * @var \Wasp\Utils\Collection
	 */
	public $userStorage;

	/**
	 * Set up class dependencies
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function __construct()
	{
		$this->userStorage = new Collection;
	}

	/**
	 * Gets a user by their token
	 *
	 * @return \Wasp\ShieldWall\User\UserContractInterface
	 * @author Dan Cox
	 */
	public function getUserByToken($token)
	{
		return $this->userStorage->get($token);
	}

	/**
	 * For the purpose of testing will be the same as above
	 *
	 * @return \Wasp\ShieldWall\User\UserContractInterface
	 * @author Dan Cox
	 */
	public function getUserByRememberToken($token)
	{
		return $this->getUserByToken($token);
	}

	/**
	 * For testing we will return the userContract
	 *
	 * @return \Wasp\ShieldWall\User\UserContractInterface
	 * @author Dan Cox
	 */
	public function getUserObjectFromContract(UserContractInterface $userContract)
	{
		return $userContract;
	}

	/**
	 * Saves the token against the user contract
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function saveToken($token, UserContractInterface $userContract)
	{
		$this->userStorage->add($token, $userContract);
	}

	/**
	 * Saves the remember token against the user record
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function saveRememberToken(Array $data, UserContractInterface $userContract)
	{
		list($key, $value) = each($data);

		$this->saveToken($value, $userContract);
	}

	/**
	 * Returns a user contract via its identifier
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function getUserContractByIdentifier($identifier)
	{
		foreach ($this->userStorage as $usr)
		{
			if ($usr->getIdentifier() == $identifier)
			{
				return $usr;
			}
		}
	}

} // END class Courier implements CourierInterface

