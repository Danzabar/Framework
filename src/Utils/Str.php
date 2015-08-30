<?php

namespace Wasp\Utils;

/**
 * A utility class that helps with strings
 *
 * @package Wasp
 * @subpackage Utils
 * @author Dan Cox
 */
class Str
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

    /**
     * Converts an array to a useful string that can be used
     * in html elements
     *
     * @param Array $arr
     * @return String
     * @author Dan Cox
     */
    public static function arrayToHtmlProperties(Array $arr)
    {
        $str = '';

        foreach ($arr as $key => $value)
        {
            $str .= sprintf(' %s="%s"', $key, $value);
        }

        return ltrim($str, ' ');
    }

} // END class String
