<?php
/**
 * Created by PhpStorm.
 * User: almasry
 * Date: 09/03/2017
 * Time: 12:42 AM
 */

namespace CommonServices\UserServiceBundle\Form\Validation;

use CommonServices\UserServiceBundle\Form\Validation\Constraint\InternationalMobileNumber;
use CommonServices\UserServiceBundle\Form\Validation\Constraint\UniqueUserEmail;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUserEmailValidator extends ConstraintValidator
{
    /**
     * @var ContainerInterface
     */
    public $serviceContainer;

    /**
     * UniqueUserMobileNumberValidator constructor.
     * @param ContainerInterface $serviceContainer
     */
    public function __construct(ContainerInterface $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * @param string $userEmail
     * @param Constraint $constraint
     */
    public function validate($userEmail, Constraint $constraint)
    {
        if(is_null($userEmail)){
            return;
        }

        try{
            $user = $this->serviceContainer->get('user_service.core')->getUserByEmail($userEmail);

            if(!is_null($user)){
                $this->buildViolation($userEmail, $constraint);
            }

        }catch(\Exception $e){
            $this->buildViolation($userEmail, $constraint);
        }
    }

    private function buildViolation(string $userEmail, Constraint $constraint){

        /** @var UniqueUserEmail $constraint */
        $this->context->buildViolation($constraint->message)
            ->setParameter('%string%', $userEmail)
            ->addViolation();
    }
}