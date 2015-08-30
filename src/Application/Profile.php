<?php namespace Wasp\Application;

/**
 * Profile class is used to set up the application
 *
 * @package Wasp
 * @subpackage Application
 * @author Dan Cox
 **/
class Profile
{

    /**
     * An associative array containing machine names and profile names
     *
     * @var Array
     **/
    protected $profiles = Array();  

    /**
     * Symfony file system
     *
     * @var \Symfony\Component\Filesystem\Filesystem
     **/
    protected $fs;

    /**
     * An Array of settings
     *
     * @var Array
     **/
    protected $settings;

    /**
     * The base directory for settings
     *
     * @var string
     **/
    protected $directory;

    /**
     * An array of expected settings files
     *
     * @var Array
     **/
    protected $files = Array();

    /**
     * Hostname of the current machine
     *
     * @var string
     **/
    public $hostname;

    /**
     * Load dependencies
     *
     * @param \Symfony\Component\Filesystem\Filesystem
     * @author Dan Cox
     **/
    public function __construct($fs)
    {
        $this->fs = $fs;
        $this->hostname = gethostname();
    }

    /**
     * Gets the settings from the files array while checking for custom settings
     *
     * @return void
     * @author Dan Cox
     **/
    public function settings()
    {
        foreach ($this->files as $file)
        {
            $this->settings[$file] = $this->extractSettings($file);
        }
    }

    /**
     * Checks for custom settings and returns the appropriate array
     *
     * @param String $file
     * @return Array
     * @author Dan Cox
     **/
    public function extractSettings($file)
    {
        $custom = Array();

        if (array_key_exists($this->hostname, $this->profiles))
        {
            $custom = $this->extractSettingsFromFile($this->profiles[$this->hostname] . '/' . $file);
        }

        $setting = $this->extractSettingsFromFile($file);

        return array_replace_recursive($setting, $custom);
    }

    /**
     * Extracts an array from the file if it exists
     *
     * @return Array
     * @author Dan Cox
     **/
    public function extractSettingsFromFile($file)
    {
        if ($this->fs->exists($this->directory . $file . '.php'))
        {
            return require ($this->directory . $file . '.php');
        }

        return Array();
    }

    /**
     * Sets the base settings directory
     *
     * @param String $directory
     * @return Profile
     * @author Dan Cox
     **/
    public function setDirectory($directory)
    {
        $this->directory = $directory;
        return $this;
    }

    /**
     * Adds expected files to the array
     *
     * @param Array|String $files
     * @param String $directory
     * @return Profile
     * @author Dan Cox
     **/
    public function addFiles($files, $directory = NULL)
    {
        if (!is_null($directory))
        {
            $this->setDirectory($directory);
        }

        if (is_array($files))
        {
            $this->files = array_merge($this->files, $files);
            return $this;
        }

        $this->files[] = $files;
        return $this;
    }

    /**
     * Adds a custom hostname profile
     *
     * @param String $hostname
     * @param String $directory
     * @return Profile
     * @author Dan Cox
     **/
    public function addProfile($hostname, $directory)
    {
        $this->profiles[$hostname] = $directory;

        return $this;
    }

    /**
     * Adds an array of profiles
     *
     * @param Array $profiles
     * @return Profile
     * @author Dan Cox
     */
    public function addProfiles(Array $profiles)
    {
        foreach ($profiles as $hostname => $directory)
        {
            $this->addProfile($hostname, $directory);
        }

        return $this;
    }

    /**
     * Returns the settings
     *
     * @return Array
     * @author Dan Cox
     **/
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Set the profile settings
     *
     * @param Array $settings
     * @return Profile
     * @author Dan Cox
     */
    public function setSettings(Array $settings)
    {   
        $this->settings = $settings;
        return $this;
    }

    /**
     * Returns the current set base directory
     *
     * @return String
     * @author Dan Cox
     **/
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Returns the files array
     *
     * @return Array
     * @author Dan Cox
     **/
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Returns the current loaded profiles
     *
     * @return Array
     * @author Dan Cox
     */
    public function getProfiles()
    {
        return $this->profiles;
    }

} // END class Profile
