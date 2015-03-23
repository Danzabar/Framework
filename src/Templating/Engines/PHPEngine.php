<?php namespace Wasp\Templating\Engines;

use Symfony\Component\Templating\PhpEngine as Engine,
	Symfony\Component\Templating\TemplateNameParser,
	Symfony\Component\Templating\Loader\FilesystemLoader,
	Wasp\DI\DependencyInjectionAwareTrait;

/**
 * The php engine for templating
 *
 * @package Wasp
 * @subpackage Templating
 * @author Dan Cox
 */
class PHPEngine
{
	use DependencyInjectionAwareTrait;

	/**
	 * An instance of the PhpEngine Class
	 *
	 * @var Object
	 */
	protected $engine;

	/**
	 * An instance of the FilesystemLoader class
	 *
	 * @var Object
	 */
	protected $loader;

	/**
	 * Creates instances of engines and loader class
	 *
	 * @author Dan Cox
	 */
	public function create()
	{
		$this->loader = new FilesystemLoader(
			$this->DI->get('template')->getDirectory() . '%name%'
		);

		$this->engine = new Engine(new TemplateNameParser, $this->loader);
	}

	/**
	 * Returns the instanced engine
	 *
	 * @return \Symfony\Component\Templating\PhpEngine
	 * @author Dan Cox
	 */
	public function getEngine()
	{
		return $this->engine;
	}


} // END class PHPEngine
