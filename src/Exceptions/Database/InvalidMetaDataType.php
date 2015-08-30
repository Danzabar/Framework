<?php namespace Wasp\Exceptions\Database;


/**
 * Exception class for the meta data type property in connections
 *
 * @package Wasp
 * @subpackage Exceptions\Database
 * @author Dan Cox
 */
class InvalidMetaDataType extends \Exception
{

    /**
     * The metadata type
     *
     * @var string
     */
    protected $metadata;

    /**
     * The metadata types allowed
     *
     * @var Array
     */
    protected $typesAllowed;

    /**
     * Fire exception
     *
     * @author Dan Cox
     */
    public function __construct($metadata, Array $typesAllowed, $code = 0, \Exception $previous = NULL)
    {
        $this->metadata = $metadata;
        $this->typesAllowed = $typesAllowed;

        parent::__construct("Invalid metadata type $metadata, allowed metadata types: " . join(',', $typesAllowed), $code, $previous);
    }

    /**
     * Returns the metadata string
     *
     * @return String
     * @author Dan Cox
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Returns the allowed metadata
     *
     * @return Array
     * @author Dan Cox
     */
    public function getAllowedTypes()
    {
        return $this->typesAllowed;
    }

} // END class InvalidMetaDataType extends \Exception


