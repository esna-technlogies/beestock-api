<?php

namespace CommonServices\UserServiceBundle\Form\Validation\Constraint;

use CommonServices\UserServiceBundle\Form\Validation\InternationalMobileNumberValidator;
use Symfony\Component\Validator\Constraint;

/**
 * Class InternationalMobileNumber
 * @package CommonServices\UserServiceBundle\Form\Validation\Constraint
 *
 * @Annotation
 */
class InternationalMobileNumber extends Constraint
{
    /**
     * @var string
     */
    public $message = 'The number %string% is not a valid mobile number.';

    /**
     * @inheritdoc
     */
    public function validatedBy()
    {
        return InternationalMobileNumberValidator::class;
    }
}