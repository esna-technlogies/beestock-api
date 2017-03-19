<?php

namespace CommonServices\UserServiceBundle\Form\Validation;

use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Form\Validation\Constraint\UniqueUserEmail;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class UniqueUserEmailValidator
 * @package CommonServices\UserServiceBundle\Form\Validation
 */
class UniqueUserEmailValidator extends ConstraintValidator
{
    /**
     * @var ContainerInterface
     */
    public $serviceContainer;

    /**
     * UniqueUserEmailValidator constructor.
     * @param ContainerInterface $serviceContainer
     */
    public function __construct(ContainerInterface $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    /**
     * @inheritdoc
     */
    public function validate($userEmail, Constraint $constraint)
    {
        if(empty($userEmail)){
            return;
        }

        try{

            /** @var User $user */
            $user = $this->serviceContainer->get('user_service.core')->getUserByEmail($userEmail);

            if(!is_null($user)){

                /** @var UniqueUserEmail $constraint */
                if($user->getUuid() === $constraint->getUuid()){
                    return;
                }
                $this->buildViolation($userEmail, $constraint);
            }

        }catch(\Exception $e){
            $this->buildViolation($userEmail, $constraint);
        }
    }

    /**
     * @param string $userEmail
     * @param Constraint $constraint
     */
    private function buildViolation(string $userEmail, Constraint $constraint){

        /** @var UniqueUserEmail $constraint */
        $this->context->buildViolation($constraint->message)
            ->setParameter('%string%', $userEmail)
            ->addViolation();
    }
}