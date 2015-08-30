<?php

use Wasp\Exceptions\Utils\InvalidMapKey;

/**
 * Test case for the invalid map key exception class
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class InvalidMapKeyTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * Fire exception
     *
     * @return void
     * @author Dan Cox
     */
    public function test_fire()
    {
        try {
            throw new InvalidMapKey("Test");
        } catch (\Exception $e) {
            $this->assertEquals("Test", $e->getKey());
        }
    }

} // END class InvalidMapKeyTest extends \PHPUnit_Framework_TestCase
