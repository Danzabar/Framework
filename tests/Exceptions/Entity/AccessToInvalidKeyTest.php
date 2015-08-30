<?php

use Wasp\Exceptions\Entity\AccessToInvalidKey;

/**
 * Test case for the Access to invalid key exception
 *
 * @package Wasp
 * @subpackage Tests\Exceptions
 * @author Dan Cox
 */
class AccessToInvalidKeyTest extends \PHPUnit_Framework_TestCase
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
            throw new AccessToInvalidKey("Test", "foo");
        } catch (\Exception $e) {
            $this->assertEquals("Test", $e->getEntity());
            $this->assertEquals("foo", $e->getKey());
        }
    }

} // END class AccessToInvalidKeyTest extends \PHPUnit_Framework_TestCase
