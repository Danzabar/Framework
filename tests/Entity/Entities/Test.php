<?php namespace Wasp\Test\Entity\Entities;

use Wasp\Entity\Entity;

/**
 * The Test Entity
 *
 * @Entity
 * @package Wasp
 * @subpackage Entity\Test
 * @author Dan Cox
 */
class Test extends Entity
{
	/**
	 * Identifier
	 *
	 * @Id
	 * @Column(name="id", type="integer")
	 * @GeneratedValue
	 * @var int
	 */
	protected $Id;

	/**
	 * A test field
	 *
	 * @Column(name="name", type="string")
	 * @var string
	 */
	protected $name;
	
} // END class Test extends Entity
