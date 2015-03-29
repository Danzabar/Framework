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
		$profile->setDirectory(__DIR__ . '/profiles/');
		$profile->addFiles(['application']);
		$profile->settings();

		$profile->getSettings();
		$this->assertEquals(['application' => ['foo' => 'bar', 'test' => 'case']], $profile->getSettings());
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
		$profile->addFiles(['application']);
		$profile->addProfile('Test', 'develop');
		$profile->hostname = 'Test';

		$profile->settings();
		$this->assertEquals(['application' => ['foo' => 'zim', 'test' => 'case']], $profile->getSettings());
		$this->assertEquals(['application'], $profile->getFiles());
		$this->assertEquals(__DIR__ . '/profiles/', $profile->getDirectory());
	}

} // END class ProfileTest extends TestCase