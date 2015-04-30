<?php namespace Wasp\Utils;

/**
 * A utility class that helps with strings
 *
 * @package Wasp
 * @subpackage Utils
 * @author Dan Cox
 */
class String
{
	
	/**
	 * Slugify a string
	 *
	 * @param String $str
	 * @param String $spaceReplacement
	 * @return String
	 * @author Dan Cox
	 */
	public static function slug($str, $spaceReplacement = '-')
	{
		$str = preg_replace('~[^\\pL\d]+~u', $spaceReplacement, $str);
		$str = trim($str, $spaceReplacement);
		$str = strtolower($str);
		$str = preg_replace('~[^-\w]+~', '', $str);

		return $str;
	}

	/**
	 * Makes the string into an id like string
	 *
	 * @param String str
	 * @return String
	 * @author Dan Cox
	 */
	public static function id($str)
	{
		return self::slug($str, '_');	
	}

} // END class String
