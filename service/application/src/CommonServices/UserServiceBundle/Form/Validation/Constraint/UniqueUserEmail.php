<?php

namespace CommonServices\UserServiceBundle\Form\Validation\Constraint;

use CommonServices\UserServiceBundle\Form\Validation\UniqueUserEmailValidator;
use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueUserEmail
 * @package CommonServices\UserServiceBundle\Form\Validation\Constraint
 *
 * @Annotation
 */
class UniqueUserEmail extends Constraint
{
    /**
     * @var string
     */
    public $message = 'USER_IS_REGISTERED_ERROR - A user with email %string% has been registered before.';

    /**
     * @inheritdoc
     */
    public function validatedBy()
    {
        return UniqueUserEmailValidator::class;
    }
}