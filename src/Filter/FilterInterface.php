<?php

namespace Wasp\Filter;

/**
 * Filter interface
 *
 * @package Wasp
 * @subpackage Filter
 * @author Dan Cox
 */
interface FilterInterface
{

    /**
     * Run the filter
     *
     * @param Object $event
     * @return void
     * @author Dan Cox
     */
    public function filter($event);
}
