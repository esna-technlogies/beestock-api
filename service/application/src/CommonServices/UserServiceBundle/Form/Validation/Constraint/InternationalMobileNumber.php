<?php

namespace CommonServices\UserServiceBundle\Form\Validation\Constraint;

use CommonServices\UserServiceBundle\Form\Validation\InternationalMobileNumberValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class InternationalMobileNumber extends Constraint
{
    /**
     * @var string
     */
    public $message = 'The number %string% is not a valid mobile number.';

    public function validatedBy()
    {
        return InternationalMobileNumberValidator::class;
    }
}