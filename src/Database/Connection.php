<?php namespace Wasp\Database;

use Wasp\Database\ConnectionCollection;

/**
 * The connection class represents a single database connection
 *
 * @package Wasp
 * @subpackage Database
 * @author Dan Cox
 */
class Connection
{
	/**
	 * Instance of the Connection Collections class
	 *
	 * @var Object
	 */
	protected $collection;

	/**
	 * Load the collection
	 *
	 * @author Dan Cox
	 */
	public function __construct(ConnectionCollection $collection)
	{
		$this->collection = $collection;
	}


} // END class Connection
