<?php

use Wasp\Test\TestCase,
	Wasp\Events\ShieldWallEvent;

/**
 * Test case for shield wall additions
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class ShieldWallTest extends TestCase
{

	/**
	 * undocumented class variable
	 *
	 * @var string
	 */
	protected $extensions = ['Wasp\Test\ShieldWall\Extension\ShieldWallExtension'];

	/**
	 * Set up test env
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function setUp()
	{
		parent::setUp();

		$contract = new Wasp\User\UserContract;
		$contract->setIdentifier('test@test.com');
		$contract->setPassword($this->DI->get('hash')->hash('password'));

		$this->DI->get('courier')->userStorage[] = $contract;

		$this->DI->get('route')->add('test.route', '/test', Array('GET'), Array('_controller' => 'Wasp\Test\Controller\Controller::returnHtml'));
	}

	/**
	 * Test the courier
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_courier()
	{
		$courier = new \Wasp\Test\ShieldWall\Extension\Courier;

		$user = $courier->getUserContractByIdentifier('fake');

		$this->assertEquals(null, $user);
	}

	/**
	 * Test authenticating a user record
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_authenticate()
	{
		$shield = $this->DI->get('shield');
		$shield->authenticate('test@test.com', 'password', true);

		$this->assertTrue($shield->isAuthenticated());
	}

	/**
	 * Test authenticating through a token
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_authenticate_token()
	{
		$shield = $this->DI->get('shield');
		$shield->authenticate('test@test.com', 'password');

		$token = $shield->getToken();

		$shield->verifyToken($token);

		$contract = $shield->user();

		// The token should change
		$this->assertNotEquals($this->DI->get('session')->get('auth/token'), $token);
		$this->assertTrue($shield->isAuthenticated());
		$this->assertInstanceOf('\Wasp\User\UserContract', $contract);
	}

	/**
	 * Test a journey through an authenticated route
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_authenticatedRoutes()
	{
		$shield = $this->DI->get('shield');
		$shield->map->loadFromYML(__DIR__ . '/Extension/map.yml');

		$response = $this->fakeRequest('/test', 'GET');

		$this->assertTrue($response->isRedirection());
	}

	/**
	 * Test a successful journey through an authenticated route
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_AuthenticatedRoute_Success()
	{
		$shield = $this->DI->get('shield');
		$shield->map->loadFromYML(__DIR__ . '/Extension/map.yml');

		$shield->authenticate('test@test.com', 'password');

		$response = $this->fakeRequest('/test', 'GET');

		$this->assertFalse($response->isRedirection());
		$this->assertTrue($response->isOK());
	}

	/**
	 * Test the remember token
	 *
	 * @return void
	 * @author Dan Cox
	 */
	public function test_remember_token()
	{
		$shield = $this->DI->get('shield');
		$shield->authenticate('test@test.com', 'password', true);

		$token = $shield->getToken();

		$request = $this->DI->get('request')
					->make('/test', 'GET')
					->cookies->set('remember', $token);

		$this->DI->get('session')->clear();

		$response = $shield->request('test.route', $request);

		$this->assertTrue($shield->isAuthenticated());
	}


} // END class ShieldWallTest extends TestCase
