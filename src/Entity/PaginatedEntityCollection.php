<?php namespace Wasp\Entity;

use Wasp\Entity\EntityCollection;

/**
 * Collection class for returning paginated results
 *
 * @package Wasp
 * @subpackage Entity
 * @author Dan Cox
 */
class PaginatedEntityCollection extends EntityCollection
{

	/**
	 * The total number of records
	 *
	 * @var String
	 */
	public $total;

	

} // END class PaginatedEntityCollection extends EntityCollection
