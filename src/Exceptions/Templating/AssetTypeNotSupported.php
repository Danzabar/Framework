<?php

namespace Wasp\Exceptions\Templating;

/**
 * Exception class for invalid asset types
 *
 * @package Wasp
 * @subpackage Exceptions
 * @author Dan Cox
 */
class AssetTypeNotSupported extends \Exception
{
    /**
     * The invalid type
     *
     * @var String
     */
    protected $type;

    /**
     * The asset name
     *
     * @var String
     */
    protected $name;

    /**
     * The allowed types
     *
     * @var Array
     */
    protected $allowed;

    /**
     * Fire exception
     *
     * @param String $type
     * @param String $name
     * @param Array $allowed
     * @param Integer $code
     * @param \Exception $previous
     */
    public function __construct($type, $name, $allowed, $code = 0, \Exception $previous = null)
    {
        $this->type = $type;
        $this->name = $name;
        $this->allowed = $allowed;

        parent::__construct(
            sprintf(
                "Invalid asset registry type used (%s) for %s asset, Allowed types are: %s",
                $type,
                $name,
                join($allowed, ',')
            ),
            $code,
            $previous
        );
    }

    /**
     * Returns the type value
     *
     * @return String
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns the name value
     *
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the allowed types
     *
     * @return Array
     */
    public function getAllowed()
    {
        return $this->allowed;
    }
} // END class AssetTypeNotSupported extends \Exception
