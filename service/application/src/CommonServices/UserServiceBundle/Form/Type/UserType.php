<?php

namespace CommonServices\UserServiceBundle\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use CommonServices\UserServiceBundle\Document\User;

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
        $builder->add('email', EmailType::class);
        $builder->add('country', TextType::class);
        $builder->add('termsAccepted', RadioType::class);
        $builder->remove('userRoles');
        $builder->add('accessInfo', AccessInfoType::class);
        $builder->add('phoneNumber', PhoneNumberType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'csrf_protection' => false,
        ));
    }
}
