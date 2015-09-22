<?php namespace Wasp\Test\Entity\Entities;

use Wasp\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Test entity to provide relationship tests
 *
 * @ORM\Entity
 * @package Wasp
 * @subpackage Entity\Test
 * @author Dan Cox
 */
class ContactDetail extends Entity
{
    /**
     * The identifier
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @var Integer
     */
    protected $id;

    /**
     * Many to one with contact object
     *
     * @ORM\ManyToOne(targetEntity="Wasp\Test\Entity\Entities\Contact", fetch="LAZY")
     * @var Object
     */
    protected $contact;

} // END class ContactDetail extends Entity
