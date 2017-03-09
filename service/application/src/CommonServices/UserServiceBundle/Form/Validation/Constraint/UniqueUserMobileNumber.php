<?php
/**
 * Created by PhpStorm.
 * User: almasry
 * Date: 09/03/2017
 * Time: 12:39 AM
 */

namespace CommonServices\UserServiceBundle\Form\Validation\Constraint;

use CommonServices\UserServiceBundle\Form\Validation\UniqueUserMobileNumberValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueUserMobileNumber extends Constraint
{
    /**
     * @var string
     */
    public $message = 'A user with mobile number %string% has been registered before';

    public function validatedBy()
    {
        return UniqueUserMobileNumberValidator::class;
    }
}