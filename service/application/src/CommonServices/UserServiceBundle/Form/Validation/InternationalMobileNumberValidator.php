<?php

namespace CommonServices\UserServiceBundle\Form\Validation;

use CommonServices\UserServiceBundle\Document\PhoneNumber;
use CommonServices\UserServiceBundle\Form\Validation\Constraint\InternationalMobileNumber;
use libphonenumber\NumberParseException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use libphonenumber\PhoneNumberUtil;

class InternationalMobileNumberValidator extends ConstraintValidator
{
    /**
     * @param mixed $mobileNumber
     * @param Constraint $constraint
     */
    public function validate($mobileNumber, Constraint $constraint)
    {
        if(is_null($mobileNumber)){
            return;
        }

        $phoneNumberUtil = PhoneNumberUtil::getInstance();

        try{
            /** @var  PhoneNumber $mobileNumber */
            $phoneNumberObject = $phoneNumberUtil->parse($mobileNumber->getNumber(), $mobileNumber->getCountryCode());

            $internationalNumber = $phoneNumberUtil->canBeInternationallyDialled($phoneNumberObject);
            $validNumber         = $phoneNumberUtil->isValidNumber($phoneNumberObject);
            $validMobileNumber   = ($phoneNumberUtil->getNumberType($phoneNumberObject) === 1);

            if (!($internationalNumber && $validNumber && $validMobileNumber))
            {
                $this->buildViolation($mobileNumber, $constraint);
            }

        }catch(NumberParseException $e){
            $this->buildViolation($mobileNumber, $constraint);
        }
    }

    private function buildViolation(PhoneNumber $phoneNumber, Constraint $constraint){

        /** @var InternationalMobileNumber $constraint */
        $this->context->buildViolation($constraint->message)
                        ->setParameter('%string%', $phoneNumber->getNumber())
                        ->atPath('mobileNumber[number]')
                        ->addViolation();
    }
}