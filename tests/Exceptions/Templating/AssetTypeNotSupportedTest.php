<?php

use Wasp\Exceptions\Templating\AssetTypeNotSupported;

/**
 * Test Case for the invalid asset type exception
 *
 * @package Wasp
 * @subpackage Test
 * @author Dan Cox
 */
class AssetTypeNotSupportedTest extends \PHPUnit_Framework_TestCase
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

            throw new AssetTypeNotSupported('test', 'name', ['valid']);

        } catch (\Exception $e) {
            $this->assertEquals('test', $e->getType());
            $this->assertEquals('name', $e->getName());
            $this->assertEquals(['valid'], $e->getAllowed());
            return;
        }

        $this->fail('no exception thrown');
    }

} // END class AssetTypeNotSupportedTest extends \PHPUnit_Framework_TestCase
