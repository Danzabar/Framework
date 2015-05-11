<?php namespace Wasp\Test\Controller;

use Wasp\Controller\BaseController;

/**
 * Just a test controller
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class ForwardedController extends BaseController
{

	/**
	 * Forwarded action
	 *
	 * @return String
	 * @author Dan Cox
	 */
	public function forward($id)
	{
		return 'forward into the unknown '. $id;
	}
	
} // END class ForwardedController extends BaseController
