<?php

use Doctrine\Fixture\Fixture,
    Wasp\DI\StaticContainerAwareTrait;

/**
 * A test fixture
 *
 */
class TestFixture implements Fixture
{
    use StaticContainerAwareTrait;

    /**
     * Import
     *
     * @return void
     * @author Dan Cox
     */
    public function import()
    {
        $test = self::get('entity')->load('Wasp\Test\Entity\Entities\Test');
        $test->name = 'jim';
        $test->save();
    }

    /**
     * Purge
     *
     * @return void
     * @author Dan Cox
     */
    public function purge()
    {
        // Remove jim
        $jim = self::get('entity')->load('Wasp\Test\Entity\Entities\Test')->findOneBy(['name' => 'jim']);
        $jim->delete();
    }

} // END class TestFixture
