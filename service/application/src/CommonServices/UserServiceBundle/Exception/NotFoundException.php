<?php

namespace CommonServices\UserServiceBundle\Exception;

use Throwable;

/**
 * Class NotFoundException
 * @package CommonServices\UserServiceBundle\Exception
 */
class NotFoundException extends \Exception
{
    public function __construct($message = "", $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}