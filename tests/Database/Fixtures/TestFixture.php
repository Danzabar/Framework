<?php

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Wasp\Test\Entity\Entities\Test;

/**
 * A test fixture
 *
 */
class TestFixture extends AbstractFixture
{
    /**
     * Load fixtures
     *
     * @return void
     * @author Dan Cox
     */
    public function load(ObjectManager $manager)
    {
        $test = new Test;
        $test->name = 'jim';
        $test->save();
    }

} // END class TestFixture
