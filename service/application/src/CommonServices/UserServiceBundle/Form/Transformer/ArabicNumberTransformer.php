<?php

namespace CommonServices\UserServiceBundle\Form\Transformer;

use CommonServices\UserServiceBundle\Document\PhoneNumber;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class ArabicNumberTransformer
 * @package CommonServices\UserServiceBundle\Form\Transformer
 */
class ArabicNumberTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($object)
    {
        /** @var PhoneNumber $object */
        $number = $object->getNumber();

        $persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabicNumbers  = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١','٠'];

        $latinNumbersRange = array_reverse(range(0, 9));

        $convertedPersianNumber = str_replace($persianNumbers, $latinNumbersRange, $number);

        $englishNumber = str_replace($arabicNumbers, $latinNumbersRange, $convertedPersianNumber);

        return $englishNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($number)
    {
        return;
    }

}