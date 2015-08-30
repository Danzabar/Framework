<?php

namespace Wasp\Entity;

use Symfony\Component\Validator\Validation as SymValidator;

/**
 * Entity Validation
 *
 * @package Wasp
 * @subpackage Entity
 * @author Dan Cox
 */
class Validator
{
    /**
     * Instance of the SymValidator
     *
     * @var \Symfony\Component\Validator\Validation
     */
    protected $validator;

    /**
     * Set up class dependencies
     *
     * @return void
     * @author Dan Cox
     */
    public function __construct()
    {
        $this->validator = SymValidator::createValidatorBuilder()
                            ->enableAnnotationMapping()
                            ->getValidator();
    }

    /**
     * Validates the specified entity against its annotated rules
     *
     * @param \Wasp\Entity\Entity
     * @return void
     * @author Dan Cox
     */
    public function validate($entity)
    {
        return $this->validator->validate($entity);
    }
} // END class Validator
