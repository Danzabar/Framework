<?php namespace Wasp\Test\Entity\Entities;

use Wasp\Entity\Entity,
    Wasp\Entity\Traits\DateStamp,
    Wasp\Entity\Traits\Slugify,
    Wasp\Entity\Traits\Suspendable,
    Doctrine\ORM\Mapping as ORM;

/**
 * The Test Entity
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @package Wasp
 * @subpackage Entity\Test
 * @author Dan Cox
 */
class Test extends Entity
{
    use DateStamp, Slugify, Suspendable;

    /**
     * Identifier
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue
     * @var int
     */
    protected $Id;

    /**
     * A test field
     *
     * @ORM\Column(name="name", type="string")
     * @var string
     */
    protected $name;
    
} // END class Test extends Entity
