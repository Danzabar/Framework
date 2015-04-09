<?php namespace Wasp\Exceptions\Database;

/**
 * Exception class for using invalid entity classes in the database mockery
 *
 * @package Wasp
 * @subpackage Exceptions\Database
 * @author Dan Cox
 */
class InvalidEntityClass extends \Exception
{

	/**
	 * The entity class used
	 *
	 * @var Object
	 */
	protected $entity;

	/**
	 * Qualified class name
	 *
	 * @var String
	 */
	protected $entityName;

	/**
	 * Fire exception
	 *
	 * @param Object $entity
	 * @param Integer $code
	 * @param \Exception $previous
	 * @author Dan Cox
	 */
	public function __construct($entity, $code = 0, \Exception $previous = NULL)
	{
		$this->entity = $entity;
		$this->entityName = get_class($entity);

		parent::__construct("An invalid entity class was used: ". $this->entityName, $code, $previous);
	}

	/**
	 * Returns the entity class
	 *
	 * @return Object
	 * @author Dan Cox
	 */
	public function getEntity()
	{	
		return $this->entity;
	}

	/**
	 * Returns the Qualified class name for the entity
	 *
	 * @return String
	 * @author Dan Cox
	 */
	public function getEntityName()
	{	
		return $this->entityName;
	}
	
} // END class InvalidEntityClass extends \Exception

