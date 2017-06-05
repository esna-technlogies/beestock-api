<?php

namespace CommonServices\PhotoBundle\Form\Transformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class AccessInfoTransformer
 * @package CommonServices\UserServiceBundle\Form\Transformer
 */
class AccessInfoTransformer implements DataTransformerInterface
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
    public function transform($accessInfo)
    {
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($passwordString)
    {
        return;
    }

}