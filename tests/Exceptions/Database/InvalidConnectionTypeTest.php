<?php

use Wasp\Exceptions\Database\InvalidConnectionType;

/**
 * Test case for the Invalid connection type exception class
 *
 * @package Wasp
 * @subpackage Test\Exceptions
 * @author Dan Cox
 */
class InvalidConnectionTypeTest extends \PHPUnit_Framework_TestCase
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
            throw new InvalidConnectionType("Test", ["Array" => "convertFromArray"]);
        } catch (\Exception $e) {
            $this->assertEquals("Test", $e->getType());
            $this->assertEquals(["Array" => "convertFromArray"], $e->getAllowed());
        }
    }
        
} // END class InvalidConnectionTypeTest extends \PHPUnit_Framework_TestCase
