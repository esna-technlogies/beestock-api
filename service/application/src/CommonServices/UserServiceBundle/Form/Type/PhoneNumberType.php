<?php

namespace CommonServices\UserServiceBundle\Form\Type;

use CommonServices\UserServiceBundle\Document\PhoneNumber;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class PhoneNumberType
 * @package CommonServices\UserServiceBundle\Form\Type
 */
class PhoneNumberType extends AbstractType
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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('countryCode', CountryType::class,
            [
                'constraints' =>
                [
                    new NotBlank(),
                ]
            ]
        );

        $builder->add('number', TextType::class,
            [
                'constraints' =>
                [
                    new NotBlank(),
                ]
            ]
        );

    }

    /**
     * {@inheritdoc}
     */

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'invalid_message' => 'The number you filled is not a valid mobile number.',
            'data_class' => PhoneNumber::class,
            'csrf_protection' => false,
        ));
    }

}