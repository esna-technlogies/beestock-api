<?php
/**
 * Created by PhpStorm.
 * User: almasry
 * Date: 28/02/2017
 * Time: 9:26 PM
 */

namespace ImageStock\UserServiceBundle\Form\Type;


use ImageStock\UserServiceBundle\Document\AccessInfo;
use ImageStock\UserServiceBundle\Document\PhoneNumber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhoneNumberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('countryCode', TextType::class);
        $builder->add('number', TextType::class);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => PhoneNumber::class,
            'csrf_protection' => false,
        ));
    }

}