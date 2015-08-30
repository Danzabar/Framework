<?php

use Wasp\Exceptions\Routing\InvalidRestOption;

/**
 * Test case for the Invalid rest option exception
 *
 * @package Wasp
 * @subpackage Tests\Exceptions
 * @author Dan Cox
 */
class InvalidRestOptionTest extends \PHPUnit_Framework_TestCase
{
    
    /**
     * Fire exception 
     *
     * @return void
     * @author Dan Cox
     */
    public function test_fireException()
    {
        try {
            throw new InvalidRestOption('test', ['foo', 'bar']);
        } catch (\Exception $e) {
            $this->assertEquals('test', $e->getOption());
            $this->assertEquals(['foo', 'bar'], $e->getList());
        }
    }

} // END class InvalidRestOptionTest extends \PHPUnit_Framework_TestCase
