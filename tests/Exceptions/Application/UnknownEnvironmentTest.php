<?php

use Wasp\Exceptions\Application\UnknownEnvironment;

/**
 * Test case for the Environment Exception
 *
 * @package Wasp
 * @subpackage Tests\Exceptions
 * @author Dan Cox
 */
class UnknownEnvironmentTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * Test firing the Exception
     *
     * @return void
     * @author Dan Cox
     */
    public function test_fire()
    {
        try {
            throw new UnknownEnvironment("Test");
        } catch (\Exception $e) {
            $this->assertEquals("Test", $e->getEnvironment());
        }
    }

} // END class UnknownEnvironmentTest extends \PHPUnit_Framework_TestCase
