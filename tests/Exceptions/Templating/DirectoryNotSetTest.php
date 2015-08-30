<?php

use Wasp\Exceptions\Templating\DirectoryNotSet;


/**
 * Test case for the directory not set exception
 *
 * @package Wasp
 * @subpackage Tests\Exceptions
 * @author Dan Cox
 */
class DirectoryNotSetTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * Test fire exception
     *
     * @author Dan Cox
     */
    public function test_fire()
    {
        try {
            throw new DirectoryNotSet;
        } catch (\Exception $e) {
            $this->assertContains("Attempted to create an instance of Delegation Engine", $e->getMessage());
        }
    }

} // END class DirectoryNotSetTest extends \PHPUnit_Framework_TestCase
