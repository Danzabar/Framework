<?php

namespace Wasp\Exceptions\Routing;

/**
 * Exception class for a wrong rest option selection
 *
 * @package Wasp
 * @subpackage Exceptions\Routing
 * @author Dan Cox
 */
class InvalidRestOption extends \Exception
{

    /**
     * The offending option
     *
     * @var string
     */
    protected $option;

    /**
     * The correct list of options
     *
     * @var Array
     */
    protected $list;

    /**
     * Fire exception
     *
     * @param String $option
     * @param Array $list
     * @param Integer $code
     * @param \Exception $exception
     * @author Dan Cox
     */
    public function __construct($option, $list, $code = 0, \Exception $previous = NULL)
    {
        $this->option = $option;
        $this->list = $list;

        parent::__construct("The rest route option $option is invalid.", $code, $previous);
    }

    /**
     * Returns the option
     *
     * @return String
     * @author Dan Cox
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * Returns the list
     *
     * @return Array
     * @author Dan Cox
     */
    public function getList()
    {
        return $this->list;
    }

} // END class InvalidRestOption extends \Exception
