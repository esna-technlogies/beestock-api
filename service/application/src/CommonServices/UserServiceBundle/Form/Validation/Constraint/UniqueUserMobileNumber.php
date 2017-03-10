<?php

namespace CommonServices\UserServiceBundle\Form\Validation\Constraint;

use CommonServices\UserServiceBundle\Form\Validation\UniqueUserMobileNumberValidator;
use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueUserMobileNumber
 * @package CommonServices\UserServiceBundle\Form\Validation\Constraint
 *
 * @Annotation
 */
class UniqueUserMobileNumber extends Constraint
{
    /**
     * @var string
     */
    public $message = 'USER_IS_REGISTERED_ERROR - A user with mobile number %string% has been registered before';

    /**
     * @inheritdoc
     */
    public function validatedBy()
    {
        return UniqueUserMobileNumberValidator::class;
    }
}