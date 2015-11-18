<?php

namespace Wasp\Templating;

use Wasp\Utils\Collection;
use Wasp\Templating\AssetOutput;
use Wasp\Exceptions\Templating\AssetTypeNotSupported;

/**
 * Assets class allows registry of global assets and a plugin to display them in twig.
 *
 * @package Wasp
 * @subpackage Templating
 * @author Dan Cox
 */
class Assets
{
    /**
     * A collection of assets
     *
     * @var Collection
     */
    protected $assets;

    /**
     * Asset output instance
     *
     * @var \Wasp\Templating\AssetOutput
     */
    protected $printer;

    /**
     * An array of allowed asset types
     *
     * @var Array
     */
    protected $allowedTypes = [
        'javascript',
        'css'
    ];

    /**
     * Set up class
     *
     */
    public function __construct()
    {
        $this->assets = new Collection;
        $this->printer = new AssetOutput;
    }

    /**
     * Checks whether the asset type is allowed, throws exception
     *
     * @param String $type
     * @param String $name - Optional
     * @return void
     * @throws \Wasp\Exceptions\Templating\AssetTypeNotSupported
     */
    public function checkAssetType($type, $name = null)
    {
        if (!in_array($type, $this->allowedTypes)) {
            throw new AssetTypeNotSupported($type, $name, $this->allowedTypes);
        }
    }

    /**
     * Returns all assets, if a type is specified it returns assets with that type
     *
     * @param String $type
     * @return String
     */
    public function all($type = null)
    {
        $assets = $this->assets->keys();

        if ($type != null) {
            $assets = $this->getAssetType($type);
        }

        return $this->getAssets($assets);
    }

    /**
     * Returns asset names of a certain type
     *
     * @return Array
     */
    public function getAssetType($type)
    {
        $assets = [];

        foreach ($this->assets as $key => $value) {
            if ($value['type'] == $type) {
                $assets[] = $key;
            }
        }

        return $assets;
    }

    /**
     * Returns html nessecary to output asset
     *
     * @param String|Array $names
     * @param Array $extras
     * @return String
     */
    public function getAssets($names, array $extras = array())
    {
        $str = '';

        if (!is_array($names)) {
            $names = array($names);
        }

        foreach ($names as $name) {
            $asset = $this->get($name);

            $str .= $this->printer->output($asset['type'], $asset['uri'], $extras);
        }

        return $str;
    }

    /**
     * Register an asset
     *
     * @param String $name - The usable asset name, eg. jquery.
     * @param String $uri - The resolvable uri for this asset.
     * @return Assets
     */
    public function register($name, $uri, $type = 'javascript')
    {
        $this->checkAssetType($type, $name);

        $this->assets->add($name, ['uri' => $uri, 'type' => $type]);
        return $this;
    }

    /**
     * Loads multiple assets
     *
     * @param Array $assets
     * @return Assets
     */
    public function registerAssets($assets)
    {
        foreach ($assets as $key => $data) {
            $this->register($key, $data['uri'], $data['type']);
        }

        return $this;
    }

    /**
     * Returns an assets type and uri from the specified name
     *
     * @return Array
     */
    public function get($name)
    {
        if ($this->has($name)) {
            return $this->assets->get($name);
        }

        return false;
    }

    /**
     * Returns a boolean value signifying whether this asset exists
     *
     * @param String $name
     * @return Boolean
     */
    public function has($name)
    {
        return $this->assets->exists($name);
    }
} // END class Assets
