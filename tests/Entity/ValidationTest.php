<?php

use Wasp\Test\Entity\Entities\Contact,
    Wasp\Test\TestCase;

/**
 * Test case for entity validation
 *
 * @package Wasp
 * @subpackage Tests\Entity
 * @author Dan Cox
 */
class ValidationTest extends TestCase
{

    /**
     * An array of passes used by this test
     *
     * @var Array
     */
    protected $passes = [
        'Wasp\DI\Pass\DatabaseMockeryPass'
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

        $this->DI->get('database')->create(ENTITIES);
    }

    /**
     * Test that basic validation works
     *
     * @return void
     * @author Dan Cox
     */
    public function test_basicValidation()
    {
        $contact = $this->DI->get('entity')->load('Wasp\Test\Entity\Entities\Contact');

        $contact->name = 'Test';

        $validator = $this->DI->get('validator');
        $errors = $validator->validate($contact);

        $this->assertEquals(1, $errors->count());
    }

} // END class ValidationTest extends TestCase
