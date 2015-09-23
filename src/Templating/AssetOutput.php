<?php

namespace Wasp\Templating;

use Wasp\Utils\TypeMapTrait;
use Wasp\Utils\Str;

/**
 * Responsible for outputting assets
 *
 * @package Wasp
 * @subpackage Templating
 * @author Dan Cox
 */
class AssetOutput
{
    use TypeMapTrait;

    /**
     * Creates type map array
     *
     * @author Dan Cox
     */
    public function __construct()
    {
        $this->typeMap = [
            'javascript'    => 'outputJavascript',
            'css'           => 'outputCss'
        ];
    }

    /**
     * Delegates the outputting of a field through the type map trait
     *
     * @param String $type
     * @param String $uri
     * @param Array $extras
     * @return String
     * @author Dan Cox
     */
    public function output($type, $uri, array $extras = array())
    {
        return $this->map($type, 'Exception', [$uri, $extras]);
    }

    /**
     * Outputs a javascript tag
     *
     * @param String $uri
     * @param Array $extras
     * @return String
     * @author Dan Cox
     */
    public function outputJavascript($uri, array $extras = array())
    {
        return sprintf(
            '<script type="text/javascript" src="%s"%s></script>',
            $uri,
            Str::arrayToHtmlProperties($extras)
        );
    }

    /**
     * Outputs a style tag
     *
     * @param String $uri
     * @param Array $extras
     * @return String
     * @author Dan Cox
     */
    public function outputCss($uri, array $extras = array())
    {
        return sprintf(
            '<link rel="Stylesheet" href="%s"%s/>',
            $uri,
            Str::arrayToHtmlProperties($extras)
        );
    }

} // END class AssetOutput
