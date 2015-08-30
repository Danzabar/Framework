<?php

use Wasp\Exceptions\Database\InvalidMetaDataType;

/**
 * Test for the Exception InvalidMetaDataType
 *
 * @package Wasp
 * @subpackage Tests\Exception
 * @author Dan Cox
 */
class InvalidMetaDataTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Just fire the exception
     *
     * @return void
     * @author Dan Cox
     */
    public function test_fire()
    {
        try {
            throw new InvalidMetaDataType("Test", ["Annotation", "None"]);
        } catch (\Exception $e) {
            $this->assertEquals("Test", $e->getMetadata());
            $this->assertEquals(["Annotation", "None"], $e->getAllowedTypes());
        }
    }

} // END class InvalidMetaDataTest extends \PHPUnit_Framework_TestCase
