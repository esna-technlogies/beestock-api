<?php
/**
 * Created by PhpStorm.
 * User: almasry
 * Date: 28/02/2017
 * Time: 9:26 PM
 */

namespace ImageStock\UserServiceBundle\Form\Type;


class AcceesInfoType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }

}