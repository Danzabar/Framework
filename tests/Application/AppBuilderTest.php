<?php

use Wasp\Application\AppBuilder;
use Wasp\Test\TestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Test case for the App builder class
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class AppBuilderTest extends TestCase
{
    /**
     * Tear down test environment
     *
     * @return void
     */
    public function tearDown()
    {
        $fs = new Filesystem;

        if ($fs->exists(__DIR__ . '/App/wasp')) {
            $fs->remove(__DIR__ . '/App/wasp');
        }
    }

    /**
     * Test building an app
     *
     * @return void
     */
    public function test_build()
    {
        $appBuilder = new AppBuilder(__DIR__ . '/App');
        $appBuilder->build('Wasp', 'App\Wasp');

        $fs = new Filesystem;

        $this->assertTrue($fs->exists(__DIR__ . '/App/wasp'));
        $this->assertTrue($fs->exists(__DIR__ . '/App/wasp/WaspEnvironment.php'));
        $this->assertTrue($fs->exists(__DIR__ . '/App/wasp/Routes.php'));
        $this->assertTrue($fs->exists(__DIR__ . '/App/wasp/src'));
        $this->assertTrue($fs->exists(__DIR__ . '/App/wasp/bootstrap'));
        $this->assertTrue($fs->exists(__DIR__ . '/App/wasp/public'));
    }
} // END class AppBuilderTest extends TestCase
