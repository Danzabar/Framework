<?php

use Doctrine\Fixture\Fixture;

/**
 * A test fixture
 *
 */
class TestFixture implements Fixture
{

	/**
	 * Import
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function import()
	{
		$test = new Wasp\Test\Entity\Entities\Test();
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
		$jim = Wasp\Test\Entity\Entities\Test::db()->findOneBy(['name' => 'jim']);
		$jim->delete();
	}
	
} // END class TestFixture
