<?php

namespace Wasp\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Wasp\Utils\Str;

/**
 * Slugify trait, adds a title and seo friendly url to the base table
 *
 * @package Wasp
 * @subpackage Entity
 * @author Dan Cox
 */
trait Slugify
{
    /**
     * The title, from which the slug is based
     *
     * @ORM\Column(name="title", type="string", nullable=TRUE)
     * @var String
     */
    protected $title;

    /**
     * The slug, seo friendly url partial
     *
     * @ORM\Column(name="slug", type="string", nullable=TRUE)
     * @var String
     */
    protected $slug;

    /**
     * Hooks onto the pre persist event to update the slug
     *
     * @ORM\PrePersist
     * @return void
     * @author Dan Cox
     */
    public function prePersistSlug()
    {
        if (!is_null($this->title)) {
            $this->slug = Str::slug($this->title);
        }
    }

    /**
     * Hooks onto the pre update event to update slug if title has changed
     *
     * @ORM\PreUpdate
     * @return void
     * @author Dan Cox
     */
    public function preUpdateSlug()
    {
        if (!is_null($this->title) && Str::slug($this->title) !== $this->slug) {
            $this->slug = Str::slug($this->title);
        }
    }
}
