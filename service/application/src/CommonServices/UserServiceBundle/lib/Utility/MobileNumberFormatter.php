<?php
/**
 * Created by PhpStorm.
 * User: almasry
 * Date: 09/03/2017
 * Time: 1:24 AM
 */

namespace CommonServices\UserServiceBundle\lib\Utility;

use CommonServices\UserServiceBundle\Document\PhoneNumber;
use CommonServices\UserServiceBundle\Exception\InvalidArgumentException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\HttpFoundation\Response;

class MobileNumberFormatter
{
    /**
     * @param string $countryCode
     * @param string $number
     * @return string
     * @throws InvalidArgumentException
     */
    public function getInternationalMobileNumber(string $number, string $countryCode)
    {
        $phoneNumberUtil = PhoneNumberUtil::getInstance();

        /** @var  PhoneNumber $phoneNumber */
        $phoneNumberObject = $phoneNumberUtil->parse($number, $countryCode);

        $internationalNumber = $phoneNumberUtil->canBeInternationallyDialled($phoneNumberObject);
        $validNumber         = $phoneNumberUtil->isValidNumber($phoneNumberObject);
        $validMobileNumber   = ($phoneNumberUtil->getNumberType($phoneNumberObject) === 1);

        if (!($internationalNumber && $validNumber && $validMobileNumber))
        {
            throw new InvalidArgumentException('Invalid international mobile number.', Response::HTTP_BAD_REQUEST);
        }

        return $phoneNumberUtil->format($phoneNumberObject, PhoneNumberFormat::E164);
    }
}