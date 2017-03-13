<?php

namespace CommonServices\UserServiceBundle\Form\Utility;

/**
 * Class EnglishNumberConverter
 * @package CommonServices\UserServiceBundle\Form\Utility
 */
class EnglishNumberConverter
{
    /**
     * @var string
     */
    protected $string;

    /**
     * EnglishNumberConverter constructor.
     * @param string $string
     */
    public function __construct($string)
    {
        $this->string = $string;
    }

    /**
     * @return string
     */
    public function convert()
    {
        $string = $this->string;

        $persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabicNumbers  = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١','٠'];

        $num = array_reverse(range(0, 9));

        $convertedPersianNumber = str_replace($persianNumbers, $num, $string);

        $englishNumber = str_replace($arabicNumbers, $num, $convertedPersianNumber);

        return $englishNumber;
    }
}