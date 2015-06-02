<?php	namespace Wasp\Test\Entity\Entities;

use Wasp\Entity\Entity,
	Doctrine\ORM\Mapping as ORM;

/**
 * A test Contact entity
 *
 * @ORM\Entity
 * @package Wasp
 * @subpackage Entity\Test
 * @author Dan Cox
 */
class Contact extends Entity
{
	/**
	 * Identifier
	 *
	 * @ORM\Id
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\GeneratedValue
	 *
	 * @var integer
	 */
	protected $id;

	/**
	 * Name
	 *
	 * @ORM\Column(name="name", type="string")
	 * @var String
	 */
	protected $name;

	/**
	 * Message
	 *
	 * @ORM\Column(name="message", type="string")
	 * @var String
	 */
	protected $message;
	
} // END class Contact extends Entity

