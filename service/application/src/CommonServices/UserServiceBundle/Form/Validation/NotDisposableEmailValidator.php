<?php

namespace CommonServices\UserServiceBundle\Form\Validation;

use CommonServices\UserServiceBundle\Form\Validation\Constraint\NotDisposableEmail;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use EmailChecker\EmailChecker;

/**
 * Class NotDisposableEmailValidator
 * @package CommonServices\UserServiceBundle\Form\Validation
 */
class NotDisposableEmailValidator extends ConstraintValidator
{
    /**
     * @inheritdoc
     */
    public function validate($userEmail, Constraint $constraint)
    {
        if(empty($userEmail)){
            return;
        }
        try{
            $checker = new EmailChecker();
            if(!$checker->isValid($userEmail)){
                $this->buildViolation($constraint);
            }
        }catch(\Exception $e){
            $this->buildViolation($constraint);
        }
    }

    /**
     * @param Constraint $constraint
     */
    private function buildViolation(Constraint $constraint){

        /** @var NotDisposableEmail $constraint */
        $this->context->buildViolation($constraint->message)->addViolation();
    }
}