<?php

namespace CommonServices\PhotoBundle\Form\Transformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class PhoneNumberTransformer
 * @package CommonServices\UserServiceBundle\Form\Transformer
 */
class PhoneNumberTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * UserType constructor.
     * @param ObjectManager $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($mobileNumber)
    {
        return $mobileNumber;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($mobileNumber)
    {
       // remove white spaces - if any
        $mobileNumber = preg_replace('#\s+#','',trim($mobileNumber));

        return $mobileNumber;
    }
}