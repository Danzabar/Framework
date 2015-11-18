<?php

namespace Wasp\Utils;

use Wasp\Exceptions\Utils\InvalidMapKey;

/**
 * Argument Mapping Trait
 *
 * @package Wasp
 * @subpackage Utils
 * @author Dan Cox
 */
trait ArgumentMappingTrait
{
    /**
     * An associative array of argument maps
     *
     * @var Array
     */
    protected $maps = [];

    /**
     * Adds an argument map
     *
     * @param String $name
     * @param Array $map
     * @return void
     */
    public function addArgumentMap($name, Array $map)
    {
        $this->maps[$name] = $map;
    }

    /**
     * Checks an argument map for a value
     *
     * @param String $mapName
     * @param String $key
     * @return Boolean
     */
    public function checkArgumentMapValue($mapName, $key)
    {
        if (array_key_exists($mapName, $this->maps)) {
            $map = $this->maps[$mapName];

            return array_key_exists($key, $map);
        }

        throw new InvalidMapKey($mapName);
    }

    /**
     * Returns the value associated with a key for a given argument map
     *
     * @param String $mapName
     * @param String $key
     * @return Mixed
     */
    public function getArgumentMapValue($mapName, $key)
    {
        if ($this->checkArgumentMapValue($mapName, $key)) {
            return $this->maps[$mapName][$key];
        }

        throw new InvalidMapKey($key);
    }
}
