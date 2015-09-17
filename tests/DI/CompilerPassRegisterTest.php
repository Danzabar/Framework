<?php

use Wasp\DI\DICompilerPassRegister;

/**
 * Test case for the Compiler Pass register
 *
 * @package Wasp
 * @subpackage Test
 * @author Dan Cox
 */
class CompilerPassRegisterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * tear down test env
     *
     * @return void
     * @author Dan Cox
     */
    public function tearDown()
    {
        $register = new DICompilerPassRegister;
        $register->clear();
    }

    /**
     * Test adding and removing compiler passes
     *
     * @return void
     * @author Dan Cox
     */
    public function test_addRemovePasses()
    {
        $register = new DICompilerPassRegister;

        $register->add('TestClass');
        $register->add(['Test2', 'Test3']);

        $register->remove('Test2');

        $this->assertEquals(['TestClass', 'Test3', 'Wasp\DI\Pass\EntityInjectionPass'], $register->getPasses());
    }

} // END class CompilerPassRegisterTest extends \PHPUnit_Framework_TestCase
