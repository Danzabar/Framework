<?php

namespace Wasp\Application;

use Symfony\Component\Filesystem\Filesystem;

/**
 * The App builder class creates new App structures through the CLI
 *
 * @package Wasp
 * @subpackage Application
 * @author Dan Cox
 */
class AppBuilder
{
    /**
     * The base directory
     *
     * @var String
     */
    protected $baseDir;

    /**
     * Instance of the filesystem class
     *
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    protected $fs;

    /**
     * The name of the App
     *
     * @var String
     */
    protected $appName;

    /**
     * Location of the App folder
     *
     * @var String
     */
    protected $appDir;

    /**
     * The src directory
     *
     * @var String
     */
    protected $appSrcDir;

    /**
     * The namespace for the app - Taken from the app name if none is given
     *
     * @var String
     */
    protected $namespace;

    /**
     * An array of config file names
     *
     * @var Array
     */
    protected $configFiles;

    /**
     * Set up class dependencies
     *
     * @param String $baseDir
     * @param Filesystem $fs
     */
    public function __construct($baseDir = null, $fs = null)
    {
        $this->baseDir = $baseDir;
        $this->fs = (!is_null($fs) ? $fs : new Filesystem);

        $this->configFiles = array(
            'application',
            'assets',
            'auth',
            'commands',
            'database',
            'extensions',
            'templates'
        );
    }

    /**
     * Build an application with the specified name
     *
     * @return void
     */
    public function build($appName, $namespace = null)
    {
        $this->appName = ucwords($appName);
        $this->namespace = (!is_null($namespace) ? ucwords($namespace) : $this->appName);

        $this->createDirectoryTree();
        $this->createCoreFiles();
    }

    /**
     * Creates the structure of the App
     *
     * @return void
     */
    protected function createDirectoryTree()
    {
        $this->appDir = sprintf('%s/%s', $this->baseDir, strtolower($this->appName));
        $this->appSrcDir = sprintf('%s/src', $this->appDir);

        $this->fs->mkdir($this->appDir);
        $this->fs->mkdir($this->appSrcDir);

        // App Folders
        $this->fs->mkdir($this->appSrcDir . '/Config');

        // Public folders
        $this->fs->mkdir($this->appDir . '/public');
        $this->fs->mkdir($this->appDir . '/bootstrap');
        $this->fs->mkdir($this->appDir . '/tests');

        // Cache
        $this->fs->mkdir($this->appDir . '/cache');
    }

    /**
     * Creates the core application setup files
     *
     * @return void
     */
    protected function createCoreFiles()
    {
        // Environment Class
        $this->fs->dumpFile(
            sprintf('%s/%sEnvironment.php', $this->appSrcDir, $this->appName),
            $this->getFileContents(__DIR__ . '/AppTemplates/environment')
        );

        // Config files
        foreach ($this->configFiles as $config) {
            $this->fs->dumpFile(
                sprintf('%s/src/Config/%s.php', $this->appDir, $config),
                $this->getFileContents(sprintf('%s/AppTemplates/Config/%s', __DIR__, $config))
            );
        }

        // Route file
        $this->fs->dumpFile(
            sprintf('%s/Routes.php', $this->appDir),
            $this->getFileContents(__DIR__ . '/AppTemplates/Routes')
        );

        // Bootstrap
        $this->fs->dumpFile(
            sprintf('%s/bootstrap/bootstrap.php', $this->appDir),
            $this->getFileContents(__DIR__ . '/AppTemplates/bootstrap')
        );

        $this->fs->dumpFile(
            sprintf('%s/tests/bootstrap.php', $this->appDir),
            $this->getFileContents(__DIR__ . '/AppTemplates/testbootstrap')
        );

        // Index
        $this->fs->dumpFile(
            sprintf('%s/public/index.php', $this->appDir),
            $this->getFileContents(__DIR__ . '/AppTemplates/index')
        );

        // CLI
        $this->fs->dumpFile(
            sprintf('%s/cli', $this->appDir),
            $this->getFileContents(__DIR__ . '/AppTemplates/cli')
        );
    }

    /**
     * Returns the unmasked file content for the given file
     *
     * @param String $file
     * @return String
     */
    private function getFileContents($file)
    {
        $contents = file_get_contents($file);

        return str_replace(
            ['[AppDir]', '[AppName]', '[AppNs]', '[BaseDir]', '[AppSrcDir]'],
            [$this->appDir, $this->appName, $this->namespace, $this->baseDir, $this->appSrcDir],
            $contents
        );
    }
} // END class AppBuilder
