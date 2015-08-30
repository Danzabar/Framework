<?php

namespace Wasp\Utils;

/**
 * Type map trait calls class functions based on types
 *
 * @package Wasp
 * @subpackage Utilities
 * @author Dan Cox
 */
Trait TypeMapTrait
{
    /**
     * An associative array of type mappings
     *
     * @var Array
     */
    protected $typeMap;

    /**
     * Calls the mapped function type
     *
     * @param String $type
     * @param String $notFoundException
     * @param Array $params
     *
     * @return Mixed
     * @author Dan Cox
     */
    public function map($type, $notFoundException = 'Exception', $params  = Array())
    {
        if (array_key_exists($type, $this->typeMap))
        {
            return call_user_func_array([$this, $this->typeMap[$type]], $params);
        } else
        {
            throw new $notFoundException($type, $this->typeMap);
        }
    }
}
