<?php

namespace CommonServices\UserServiceBundle\Form\Type;

use CommonServices\UserServiceBundle\Document\AccessInfo;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class AccessInfoType
 * @package CommonServices\UserServiceBundle\Form\Type
 */
class AccessInfoType extends AbstractType
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
        $builder->add('password', PasswordType::class,
            [
                'constraints' => array(
                    new NotBlank(),
                    new Length(['min' => 6, 'max'=> 16])
                )
            ]
        );

        //$builder->add('lastChange', DateTimeType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'invalid_message' => 'The password you entered is not valid.',
            'data_class' => AccessInfo::class,
            'csrf_protection' => false,
        ));
    }

}