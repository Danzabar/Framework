<?php

use Wasp\Exceptions\Database\InvalidConnection;

/**
 * Test case for the Invalid Connection exception
 *
 * @package Wasp
 * @subpackage Tests\Exceptions
 * @author Dan Cox
 */
class InvalidConnectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test firing the exception
     *
     * @return void
     * @author Dan Cox
     */
    public function test_fire()
    {
        try {
            throw new InvalidConnection("Test");
        } catch (\Exception $e) {
            $this->assertEquals("Test", $e->getConnection());
        }
    }

} // END class InvalidConnectionTest extends \PHPUnit_Framework_TestCase
