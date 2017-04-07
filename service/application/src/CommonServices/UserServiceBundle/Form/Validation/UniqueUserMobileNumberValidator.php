<?php

namespace CommonServices\UserServiceBundle\Form\Validation;

use CommonServices\UserServiceBundle\Document\User;
use CommonServices\UserServiceBundle\Form\Validation\Constraint\UniqueUserMobileNumber;
use CommonServices\UserServiceBundle\Utility\MobileNumberFormatter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class UniqueUserMobileNumberValidator
 * @package CommonServices\UserServiceBundle\Form\Validation
 */
class UniqueUserMobileNumberValidator extends ConstraintValidator
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
     * @inheritdoc
     */
    public function validate($mobileNumber, Constraint $constraint)
    {
        if(is_null($mobileNumber->getNumber()) || is_null($mobileNumber->getCountryCode())){
            return;
        }
        try{
            $mobileNumberFormatter = new MobileNumberFormatter($mobileNumber->getNumber(), $mobileNumber->getCountryCode());
            $internationalMobileNumber = $mobileNumberFormatter->getInternationalMobileNumber();

            /** @var User $user */
            $user = $this->serviceContainer->get('user_service.user_domain')->getUserRepository()->findOneByMobileNumber($internationalMobileNumber);

            if(!is_null($user)){

                /** @var UniqueUserMobileNumber $constraint */
                if($user->getUuid() === $constraint->getUuid()){
                    return;
                }
                $this->buildViolation($mobileNumber->getNumber(), $constraint);
            }

        }catch(\Exception $e){
            $this->buildViolation($mobileNumber->getNumber(), $constraint);
        }
    }

    /**
     * @param string $mobileNumber
     * @param Constraint $constraint
     */
    private function buildViolation(string $mobileNumber, Constraint $constraint){

        /** @var UniqueUserMobileNumber $constraint */
        $this->context->buildViolation($constraint->message)
            ->setParameter('%string%', $mobileNumber)
            ->atPath('mobileNumber[number]')
            ->addViolation();
    }

}