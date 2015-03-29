<?php namespace Wasp\Templating\Engines;

use Symfony\Component\Templating\PhpEngine as Engine,
	Symfony\Component\Templating\TemplateNameParser,
	Symfony\Component\Templating\Loader\FilesystemLoader,
	Symfony\Component\Templating\Helper\SlotsHelper;

/**
 * The php engine for templating
 *
 * @package Wasp
 * @subpackage Templating
 * @author Dan Cox
 */
class PHPEngine
{
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
	 * Instanced Template class
	 *
	 * @var Wasp\Templating\Template
	 **/
	protected $template;

	/**
	 * Add dependencies
	 *
	 * @param Wasp\Templating\Template
	 * @author Dan Cox
	 **/
	public function __construct($template)
	{
		$this->template = $template;
	}

	/**
	 * Creates instances of engines and loader class
	 *
	 * @author Dan Cox
	 */
	public function create()
	{
		$this->loader = new FilesystemLoader(
			$this->template->getDirectory() . '%name%'
		);

		$this->engine = new Engine(new TemplateNameParser, $this->loader);
		$this->registerHelpers();
	}

	/**
	 * Registers standard helpers
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function registerHelpers()
	{
		$this->engine->set(new SlotsHelper);
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
