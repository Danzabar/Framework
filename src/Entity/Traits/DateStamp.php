<?php namespace Wasp\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;


/**
 * The Date stamp trait adds two date field rows and events to update them
 *
 * @package Wasp
 * @subpackage Entity
 * @author Dan Cox
 */
Trait DateStamp
{
    /**
     * Created At DateTime field
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=TRUE)
     * @var DateTime
     */
    protected $createdAt;

    /**
     * Updated at DateTime field
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=TRUE)
     * @var DateTime
     */
    protected $updatedAt;

    /**
     * Fired when the record is first persisted
     *
     * @ORM\PrePersist
     * @return void
     * @author Dan Cox
     */
    public function createDateStamp()
    {
        $this->createdAt = new \DateTime('NOW');
        $this->updatedAt = new \DateTime('NOW');
    }

    /**
     * Fired when the record is saved
     *
     * @ORM\PreUpdate
     * @return void
     * @author Dan Cox
     */
    public function updateDateStamp()
    {
        $this->updatedAt = new \DateTime('NOW');
    }

}
