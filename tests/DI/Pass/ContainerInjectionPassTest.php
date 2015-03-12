<?php

use Wasp\DI\Pass\ContainerInjectionPass,
	Wasp\DI\ServiceMockery,
	Wasp\DI\Pass\MockeryPass,
	Wasp\DI\DI;

/**
 * Test case for the Container Injection pass
 *
 * @package Wasp
 * @subpackage Tests\DI
 * @author Dan Cox
 */
class ContainerInjectionPassTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * The DI Instance
	 *
	 * @var Object
	 */
	protected $DI;

	/**
	 * Setup test env
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{
		$this->DI = new DI(dirname(__DIR__));
		$this->DI->build()->load('service');
	}

	/**
	 * Test compiling and registering the pass
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_compileAndRegister()
	{
		$this->DI->addCompilerPass(new ContainerInjectionPass);
		$this->DI->compile();
		
		// Now the Service Class should have the Container Property set
		$this->assertInstanceOf('Symfony\Component\DependencyInjection\ContainerBuilder', $this->DI->get('service')->getDI());
	}

	/**
	 * Use the config class from the Injected container
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_useClassFromDIWithinService()
	{
		$this->DI->addCompilerPass(new ContainerInjectionPass);
		$this->DI->compile();

		$this->assertInstanceOf('Symfony\Component\Filesystem\Filesystem', $this->DI->get('service')->fs());
	}

	/**
	 * Test the combination of mockery and container injection passes
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_containerAndMockeryPass()
	{
		$mock = new ServiceMockery('library');
		$mock->add();

		$this->DI->addCompilerPass(new ContainerInjectionPass);
		$this->DI->addCompilerPass(new MockeryPass);
		$this->DI->compile();

		$this->DI->get('library')->shouldReceive('foo')->andReturn('bar');

		$this->assertEquals('bar', $this->DI->get('service')->useLibrary());
	}

} // END class ContainerInjectionPassTest extends \PHPUnit_Framework_TestCase
