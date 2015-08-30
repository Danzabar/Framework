<?php

namespace Wasp\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait to make objects suspendable. uses two date fields
 *
 * @package Wasp
 * @subpackage Traits
 * @author Dan Cox
 */
trait Suspendable
{
    /**
     * The Start date of suspension
     *
     * @ORM\Column(name="suspension_start", type="datetime", nullable=TRUE)
     * @var DateTime
     */
    protected $suspendedFrom;

    /**
     * The End date of the suspension
     *
     * @ORM\Column(name="suspension_finish", type="datetime", nullable=TRUE)
     * @var DateTime
     */
    protected $suspendedUntil;

    /**
     * Adds suspension data
     *
     * @param Array $conditions
     * @return void
     * @author Dan Cox
     */
    public function suspend(Array $conditions)
    {
        $this->suspendedFrom = new \DateTime('NOW');
        $this->suspendedUntil = new \DateTime('NOW');

        foreach ($conditions as $condition) {
            $this->suspendedUntil->modify($condition);
        }

        $this->save();
    }
}
