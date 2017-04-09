<?php

namespace CommonServices\UserServiceBundle\Utility\Formatter;

use CommonServices\UserServiceBundle\Document\PhoneNumber;
use CommonServices\UserServiceBundle\Exception\InvalidArgumentException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MobileNumberFormatter
 * @package CommonServices\UserServiceBundle\Utility
 */
class MobileNumberFormatter
{
    /**
     * @var PhoneNumberUtil
     */
    private $phoneNumberUtil;

    /**
     * @var \libphonenumber\PhoneNumber
     */
    private $phoneNumberObject;
    /**
     * @var string
     */
    private $number;
    /**
     * @var string
     */
    private $countryCode;

    /**
     * @param string $number
     * @param string $countryCode
     * MobileNumberFormatter constructor
     * @throws InvalidArgumentException
     */
    public function __construct(string $number, string $countryCode)
    {
        $this->phoneNumberUtil = PhoneNumberUtil::getInstance();
        $this->phoneNumberObject = $this->phoneNumberUtil->parse($number, $countryCode);
        $this->number = $number;
        $this->countryCode = strtoupper($countryCode);
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function getInternationalMobileNumber()
    {
        $internationalMobileNumber = $this->phoneNumberUtil->format($this->phoneNumberObject, PhoneNumberFormat::E164);

        return self::cleanseMobileNumber($internationalMobileNumber);
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function getNationalMobileNumber()
    {
        $nationalMobileNumber =  $this->phoneNumberUtil->format($this->phoneNumberObject, PhoneNumberFormat::NATIONAL);

        return self::cleanseMobileNumber($nationalMobileNumber);

    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function getInternationalMobileNumberForCalling()
    {
        $callingFormat = str_replace('+', '00', $this->getInternationalMobileNumber());

        return self::cleanseMobileNumber($callingFormat);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function validateMobileNumber()
    {
        $internationalNumber = $this->phoneNumberUtil->canBeInternationallyDialled($this->phoneNumberObject);
        $validNumber         = $this->phoneNumberUtil->isValidNumber($this->phoneNumberObject);
        $validMobileNumber   = ($this->phoneNumberUtil->getNumberType($this->phoneNumberObject) === 1);

        if (!($internationalNumber && $validNumber && $validMobileNumber))
        {
            throw new InvalidArgumentException('Invalid international mobile number.');
        }
    }

    /**
     * @param $mobileNumber
     * @return string
     */
    public static function getCleansedMobileNumberAsPossibleUsername($mobileNumber)
    {
        $callingFormat = str_replace('+', '00', $mobileNumber);

        return self::cleanseMobileNumber($callingFormat);
    }

    /**
     * @param string $mobileNumber
     * @return string
     */
    private static function cleanseMobileNumber(string $mobileNumber)
    {
        return preg_replace('#\s+#','',trim($mobileNumber));
    }
}