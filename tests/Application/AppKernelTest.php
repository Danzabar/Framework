<?php 

use Wasp\Test\TestCase,
	Symfony\Bundle\SecurityBundle\SecurityBundle,
	Wasp\Application\AppKernel;

/**
 * Test case for the app kernel extension class
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class AppKernelTest extends TestCase
{
	/**
	 * Test loading the security bundle
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_registerSecurityBundle()
	{
		AppKernel::addBundles([ new SecurityBundle ]);

	}

} // END class AppKernelTest extends TestCase
