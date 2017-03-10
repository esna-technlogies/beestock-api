<?php

namespace CommonServices\UserServiceBundle\Form\Type;

use CommonServices\UserServiceBundle\Form\Validation\Constraint\InternationalMobileNumber;
use CommonServices\UserServiceBundle\Form\Validation\Constraint\UniqueUserEmail;
use CommonServices\UserServiceBundle\Form\Validation\Constraint\UniqueUserMobileNumber;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use CommonServices\UserServiceBundle\Document\User;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class UserType
 * @package CommonServices\UserServiceBundle\Form\Type
 */
class UserType extends AbstractType
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
        $builder->add('firstName', TextType::class);
        $builder->add('lastName', TextType::class);
        $builder->add('email', EmailType::class,
            [
                'constraints' =>
                    [
                        new NotNull(),
                        new UniqueUserEmail(),
                    ]
            ]
        );
        $builder->add('country', TextType::class);
        $builder->add('termsAccepted', RadioType::class);
        $builder->add('accessInfo', AccessInfoType::class);
        $builder->add('mobileNumber', PhoneNumberType::class,
            [
                'error_bubbling'=> false,
                'constraints' =>
                    [
                        new NotNull(),
                        new UniqueUserMobileNumber(),
                        new InternationalMobileNumber(),
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
            'allow_extra_fields' => true,
            'data_class' => User::class,
            'csrf_protection' => false,
        ));
    }
}
