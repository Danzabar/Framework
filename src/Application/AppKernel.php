<?php namespace Wasp\Application;

use Symfony\Component\HttpKernel\Kernel,
	Symfony\Component\Config\Loader\LoaderInterface;

/**
 * App kernel an extension of the symfony kernel to provide bundle support
 *
 * @package Wasp
 * @subpackage Application
 * @author Dan Cox
 */
class AppKernel extends Kernel
{
	/**
	 * An array of bundles to load
	 *
	 * @var Array
	 */
	protected static $bundleList = Array();

	/**
	 * Adds bundles to the bundles var which get registered later
	 *
	 * @param BundleInterface|BundleInterface[] $bundles
	 * @return void
	 * @author Dan Cox
	 */
	public static function addBundles($bundles)
	{
		if (is_array($bundles))
		{
			static::$bundleList = array_merge(static::$bundleList, $bundles);
			return;
		} 
			
		static::$bundleList[] = $bundles;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function registerContainerConfiguration(LoaderInterface $loader)
	{

	}

	/**
	 * Returns the array of bundles 
	 *
	 * @return BundleInterface[]
	 * @author Dan Cox
	 */
	public static function showBundles()
	{
		return static::$bundleList;
	}

	/**
	 * Registers bundles
	 *
	 * @return BundleInterface[]
	 * @author Dan Cox
	 */
	public function registerBundles()
	{
		return static::$bundleList;
	}

} // END class AppKernel extends Kernel
