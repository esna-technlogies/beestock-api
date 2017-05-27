<?php

namespace CommonServices\UserServiceBundle\Utility\Security;

/**
 * Class RandomCodeGenerator
 * @package CommonServices\UserServiceBundle\Utility\Security
 */
class RandomCodeGenerator
{
    /**
     * @param int $length
     * @return string
     */
    public static function generateRandomVerificationString(int $length = 6) : string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';

        $charactersLength = strlen($characters);

        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}