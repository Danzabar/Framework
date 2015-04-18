<?php namespace Wasp\Utils;

/**
 * Wrapper around the Serializer library, so it plays nice with DI
 *
 * @package Wasp
 * @subpackage Utils
 * @author Dan Cox
 */
class Serializer
{
	
	/**
	 * Instance of the serializer
	 *
	 * @var Object
	 */
	protected $serializer;

	/**
	 * undocumented function
	 *
	 * @param String $cache_dir
	 * @param Boolean $debug
	 * @author Dan Cox
	 */
	public function config($cache_dir = NULL, $debug = TRUE)
	{
		$builder = \JMS\Serializer\SerializerBuilder::create()
					->setDebug($debug);

		if (!is_null($cache_dir))
		{
			$builder->setCacheDir($cache_dir);
		}

		return $this->serializer = $builder->build();
	}

	/**
	 * Call functions straight from the serializer
	 *
	 * @param String $method
	 * @param Array $params
	 * @return Mixed
	 * @author Dan Cox
	 */
	public function __call($method, Array $params = Array())
	{
		if (is_null($this->serializer))
		{
			$this->config();
		}

		return call_user_func_array([$this->serializer, $method], $params);
	}

} // END class Serializer
