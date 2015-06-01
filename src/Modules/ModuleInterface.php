<?php namespace Wasp\Modules;

use Wasp\Modules\ModuleBuilder;

/**
 * Module Interface
 *
 * @package Wasp
 * @subpackage Modules
 * @author Dan Cox
 */
Interface ModuleInterface
{

	/**
	 * Setup module settings through the builder
	 *
	 * @param ModuleBuilder $builder
	 * @return void
	 * @author Dan Cox
	 */
	public function setup(ModuleBuilder $builder);

}
