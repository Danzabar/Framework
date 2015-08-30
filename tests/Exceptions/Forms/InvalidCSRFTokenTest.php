<?php

use Wasp\Exceptions\Forms\InvalidCSRFToken;

/**
 * Test case for the invalid CSRF Token exception
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class InvalidCSRFTokenTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test firing exception
     *
     * @return void
     * @author Dan Cox
     */
    public function test_fire()
    {
        try {   

            throw new InvalidCSRFToken;

        } catch (Exception $e) {
            $this->assertContains('CSRF token provided is invalid', $e->getMessage());
            return;
        }

        $this->fail('Exception did not fire correctly');
    }

} // END class InvalidCSRFTokenTest extends \PHPUnit_Framework_TestCase
