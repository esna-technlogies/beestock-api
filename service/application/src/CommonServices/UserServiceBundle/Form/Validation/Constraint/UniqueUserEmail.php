<?php

namespace CommonServices\UserServiceBundle\Form\Validation\Constraint;

use CommonServices\UserServiceBundle\Form\Validation\UniqueUserEmailValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueUserEmail extends Constraint
{
    /**
     * @var string
     */
    public $message = 'A user with email %string% has been registered before.';

    public function validatedBy()
    {
        return UniqueUserEmailValidator::class;
    }
}