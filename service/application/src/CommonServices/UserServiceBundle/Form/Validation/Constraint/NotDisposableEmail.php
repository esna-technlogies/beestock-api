<?php

namespace CommonServices\UserServiceBundle\Form\Validation\Constraint;

use CommonServices\UserServiceBundle\Form\Validation\NotDisposableEmailValidator;
use Symfony\Component\Validator\Constraint;

/**
 * Class NotDisposableEmail
 * @package CommonServices\UserServiceBundle\Form\Validation\Constraint
 *
 * @Annotation
 */
class NotDisposableEmail extends Constraint
{
    /**
     * @var string
     */
    public $message = 'Using a disposable email is not allowed.';

    /**
     * @inheritdoc
     */
    public function validatedBy()
    {
        return NotDisposableEmailValidator::class;
    }
}