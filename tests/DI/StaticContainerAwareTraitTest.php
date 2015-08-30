<?php

use Wasp\Test\DI\StaticDITestClass,
    Wasp\DI\DI;

/**
 * Test Case for the static container aware trait
 *
 * @package Wasp
 * @subpackage Test
 * @author Dan Cox
 */
class StaticContainerAwareTraitTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Setup test environment
     *
     * @return void
     * @author Dan Cox
     */
    public function setUp()
    {
        $di = new DI;
        $di->setDirectory(__DIR__);
        $di->build()->load('service');
    }

    /**
     * Test getting a service
     *
     * @return void
     * @author Dan Cox
     */
    public function test_getService()
    {
        $this->assertInstanceOf('Library', StaticDITestClass::get('library'));
    }

    /**
     * Test getting a parameter
     *
     * @return void
     * @author Dan Cox
     */
    public function test_getParam()
    {
        $this->assertEquals('value', StaticDITestClass::param('test'));
    }

} // END class StaticContainerAwareTraitTest extends \PHPUnit_Framework_TestCase
