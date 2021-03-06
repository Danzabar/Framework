<?php

use Wasp\Test\TestCase;

/**
 * Test Case for the profile class
 *
 * @package Wasp
 * @subpackage Tests\Application
 * @author Dan Cox
 **/
class ProfileTest extends TestCase
{

    /**
     * Test loading settings
     *
     * @return void
     * @author Dan Cox
     **/
    public function test_loadingBasicSettings()
    {
        $profile = $this->DI->get('profile');
        $profile->addFiles(['application'], __DIR__ . '/profiles/');
        $profile->settings();

        $profile->getSettings();
        $this->assertEquals(['application' => ['foo' => 'bar', 'test' => 'case']], $profile->getSettings()->all());
    }

    /**
     * test adding host name with custom settings
     *
     * @return void
     * @author Dan Cox
     **/
    public function test_customHostnameSettings()
    {
        $profile = $this->DI->get('profile');
        $profile->setDirectory(__DIR__ . '/profiles/');
        $profile->addFiles('application');
        $profile->addProfile('Test', 'develop');
        $profile->hostname = 'Test';

        $profile->settings();
        $this->assertEquals(['application' => ['foo' => 'zim', 'test' => 'case']], $profile->getSettings()->all());
        $this->assertEquals(['application'], $profile->getFiles());
        $this->assertEquals(__DIR__ . '/profiles/', $profile->getDirectory());
    }

    /**
     * Test adding an array of profiles at once
     *
     * @return void
     * @author Dan Cox
     */
    public function test_addingAnArrayOfProfiles()
    {
        $profile = $this->DI->get('profile');
        $profile->addProfiles([
            'test1'     => 'develop',
            'test2'     => 'production'
        ]);

        $this->assertContains('develop', $profile->getProfiles());
        $this->assertContains('production', $profile->getProfiles());
    }

    /**
     * Test that an empty array is returned if the file doesnt exist
     *
     * @return void
     * @author Dan Cox
     */
    public function test_fileDoesNotExist()
    {
        $profile = $this->DI->get('profile');
        $profile->setDirectory(__DIR__ . '/profiles/');
        $profile->addFiles('test');
        $profile->settings();

        $this->assertEquals(['test' => []], $profile->getSettings()->all());
    }

} // END class ProfileTest extends TestCase
