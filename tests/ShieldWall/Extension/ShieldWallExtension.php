<?php namespace Wasp\Test\ShieldWall\Extension;

use Wasp\DI\Extension;

/**
 * Extension class to provide shield wall customization, **Test**
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class ShieldWallExtension extends Extension
{

	/**
	 * The extension alias
	 *
	 * @var String
	 */
	protected $alias = 'shieldwall';

	/**
	 * The directory for the loaded
	 *
	 * @var String
	 */
	protected $directory;

	/**
	 * Set up extension
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setup()
	{
		$this->directory = __DIR__;
	}

	/**
	 * The extension
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function extension()
	{
		$this->loader->load('shieldwall.yml');
	}

} // END class ShieldWallExtension extends Extension

