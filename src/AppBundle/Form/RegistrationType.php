<?php
// src/AppBundle/Form/RegistrationType.php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name',TextType::class,array(
                     'label'=>'Nom'))
                ->add('firstName',TextType::class,array(
                    'label'=>'Prénom '))
                ->add('cin',IntegerType::class ,
                    array('label'=>'CIN '))
                ->add('address',TextareaType::class,array(
                    'label'=>'Adresse '))
                ->add('phoneNumber',IntegerType::class,array(
                    'label'=>'Numéros de téléphone '))
        ;
    }
    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }
    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }
}