<?php

use Wasp\Utils\Str;

/**
 * Test case for the string helper
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class StringTest extends \PHPUnit_Framework_TestCase
{
	
	/**
	 * Test slugifing some words
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_slug()
	{
		$slug1 = Str::slug('Foo bar');
		$slug2 = Str::slug('@&^!(@ Foo Bar');
		$slug3 = Str::slug('::":A:EOS Slugify Me');

		$this->assertEquals('foo-bar', $slug1);
		$this->assertEquals('foo-bar', $slug2);
		$this->assertEquals('a-eos-slugify-me', $slug3);
	}

	/**
	 * Test turning a string into an id
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_id()
	{
		$id1 = Str::id('Foo bar');
		$id2 = Str::id('(*&!^@(*@)(@ my id');

		$this->assertEquals('foo_bar', $id1);
		$this->assertEquals('my_id', $id2);
	}

	/**
	 * Test converting an array to html properties
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_htmlProperties()
	{
		$arr = Array('class' => 'my-class', 'data-object' => '5');

		$this->assertEquals('class="my-class" data-object="5"', Str::arrayToHtmlProperties($arr));
	}


} // END class StringTest extends \PHPUnit_Framework_TestCase
