<?php

use Wasp\Utils\ArgumentMappingTrait;

/**
 * Test case for the argument mapping trait
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class ArgumentMappingTraitTest extends \PHPUnit_Framework_TestCase
{
    use ArgumentMappingTrait;

    /**
     * It should throw an exception when given the wrong map name
     *
     * @return void
     * @author Dan Cox
     */
    public function test_shouldThrowAnExceptionWithWrongMapName()
    {
        $this->setExpectedException("Wasp\Exceptions\Utils\InvalidMapKey");

        $this->checkArgumentMapValue('Wrong', 'key');
    }

    /**
     * Same as above but one level down
     *
     * @return void
     * @author Dan Cox
     */
    public function test_shouldThrowAnExceptionWhenGivenTheWrongKey()
    {
        $this->setExpectedException("Wasp\Exceptions\Utils\InvalidMapKey");

        $this->addArgumentMap('test', []);

        $this->getArgumentMapValue('test', 'wrong');
    }

} // END class ArgumentMappingTraitTest extends \PHPUnit_Framework_TestCase
