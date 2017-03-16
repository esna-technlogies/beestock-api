<?php

namespace CommonServices\UserServiceBundle\Form\Validation;

use CommonServices\UserServiceBundle\Document\PhoneNumber;
use CommonServices\UserServiceBundle\Form\Validation\Constraint\InternationalMobileNumber;
use libphonenumber\NumberParseException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use libphonenumber\PhoneNumberUtil;

/**
 * Class InternationalMobileNumberValidator
 * @package CommonServices\UserServiceBundle\Form\Validation
 */
class InternationalMobileNumberValidator extends ConstraintValidator
{
    /**
     * @inheritdoc
     */
    public function validate($mobileNumber, Constraint $constraint)
    {
        /** @var  PhoneNumber $mobileNumber */
        if(empty($mobileNumber->getNumber())){
            return;
        }

        $phoneNumberUtil = PhoneNumberUtil::getInstance();

        try{
            $phoneNumberObject   = $phoneNumberUtil->parse($mobileNumber->getNumber(), $mobileNumber->getCountryCode());
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

    /**
     * @param PhoneNumber $phoneNumber
     * @param Constraint $constraint
     */
    private function buildViolation(PhoneNumber $phoneNumber, Constraint $constraint){

        /** @var InternationalMobileNumber $constraint */
        $this->context->buildViolation($constraint->message)
                        ->setParameter('%string%', $phoneNumber->getNumber())
                        ->atPath('mobileNumber[number]')
                        ->addViolation();
    }
}