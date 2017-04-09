<?php

namespace CommonServices\UserServiceBundle\Utility\Formatter;

use CommonServices\UserServiceBundle\Document\PhoneNumber;
use CommonServices\UserServiceBundle\Exception\InvalidArgumentException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class EmailFormatter
 * @package CommonServices\UserServiceBundle\Utility
 */
class EmailFormatter
{
    /**
     * @var string
     */
    private $email;

    /**
     * @param string $email
     * EmailFormatter constructor
     * @throws InvalidArgumentException
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * @param string $email
     * @return string
     */
    public static function getCleansedEmailAddress(string $email)
    {
        // removing white spaces if any
        $email = preg_replace('#\s+#','',trim($email));

        // lowering characters case
        $email = strtolower($email);

        return $email;
    }
}