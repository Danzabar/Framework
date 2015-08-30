<?php

use Wasp\Test\TestCase,
    Symfony\Component\HttpKernel\KernelEvents;

/**
 * Test class for filters
 *
 * @package Wasp
 * @subpackage Tests
 * @author Dan Cox
 */
class FilterTest extends TestCase
{
    /**
     * List of extensions to enable
     *
     * @var Array
     */
    protected $extensions = [
        'Wasp\Test\DI\Extension\FilterExtension'
    ];

    /**
     * Set up test env
     *
     * @return void
     * @author Dan Cox
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Test a before filter
     *
     * @return void
     * @author Dan Cox
     */
    public function test_before()
    {
        $this->DI->get('route')
            ->add('filter.before', '/before', ['GET'], ['before' => ['filter.test'], '_controller' => 'Wasp\Test\Controller\Controller::returnObject']);

        $response = $this->fakeRequest('/before', 'GET');

        $this->assertTrue($response->isRedirect());
    }

    /**
     * Similar to above but for after
     *
     * @return void
     * @author Dan Cox
     */
    public function test_after()
    {
        $this->DI->get('route')
                ->add('filter.after', '/after', ['GET'], ['after' => ['filter.test'], '_controller' => 'Wasp\Test\Controller\Controller::returnObject']);

        $response = $this->fakeRequest('/after', 'GET');

        $this->assertTrue($response->isRedirect());
    }

} // END class FilterTest extends TestCase
