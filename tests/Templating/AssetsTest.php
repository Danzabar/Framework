<?php

use Wasp\Test\TestCase;

/**
 * Test Case for the Assets template module
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class AssetsTest extends TestCase
{
    /**
     * Instance of the assets class
     *
     * @var Wasp\Templating\Assets
     */
    protected $assets;

    /**
     * Set up test env
     *
     * @return void
     * @author Dan Cox
     */
    public function setUp()
    {
        parent::setUp();

        $this->assets = $this->DI->get('assets');
    }

    /**
     * Test registering and outputting a javascript and stylesheet
     *
     * @return void
     * @author Dan Cox
     */
    public function test_register_and_output()
    {
        $this->assets->register('test', 'js/test.js', 'javascript');
        $this->assets->register('css', 'css/test.css', 'css');

        $output = $this->assets->getAssets(['test', 'css']);

        $this->assertContains('js/test.js', $output);
        $this->assertContains('css/test.css', $output);
        $this->assertTrue($this->assets->has('test'));
    }

    /**
     * Test that an invalid asset type throws an excepiton
     *
     * @return void
     * @author Dan Cox
     */
    public function test_invalid_type_throws_exception()
    {
        $this->setExpectedException('Wasp\Exceptions\Templating\AssetTypeNotSupported');

        $this->assets->register('tets', 'test', 'test');
    }

    /**
     * Test that we can get assets from types
     *
     * @return void
     * @author Dan Cox
     */
    public function test_get_assets_of_specific_type()
    {
        $this->assets->register('test', 'js.js', 'javascript');
        $this->assets->register('test2', 'js.js', 'javascript');

        $output = $this->assets->all('javascript');

        $this->assertNotContains('css', $output);
        $this->assertContains('js.js', $output);
    }

    /**
     * Test getting all with no filter
     *
     * @return void
     * @author Dan Cox
     */
    public function test_get_all()
    {
        $this->assets->register('test', 'js.js', 'javascript');
        $this->assets->register('css', 'css.css', 'css');

        $output = $this->assets->all();

        $this->assertContains('js.js', $output);
        $this->assertContains('css.css', $output);
    }

} // END class AssetsTest extends TestCase

