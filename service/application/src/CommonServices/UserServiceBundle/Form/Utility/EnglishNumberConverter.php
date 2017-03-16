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
        $number = $this->string;

        $persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabicNumbers  = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١','٠'];

        $latinNumbersRange = array_reverse(range(0, 9));

        $convertedPersianNumber = str_replace($persianNumbers, $latinNumbersRange, $number);

        $englishNumber = str_replace($arabicNumbers, $latinNumbersRange, $convertedPersianNumber);

        return $englishNumber;
    }
}