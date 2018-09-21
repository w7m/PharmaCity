<?php

namespace AppBundle\Form;

use AppBundle\Entity\Doctor;
use AppBundle\Entity\Patient;
use AppBundle\Entity\Pharmacy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PrescriptionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('status',TextType::class,array('label'=>'Le status'))
                ->add('patient',EntityType::class,array(
                    'class' => Patient::class,
                    'choice_label'=>'user',
                    'multiple'=>false,
                    'expanded'=>true,
                    'label'=>'choisissez le Patient : '))
                ->add('pharmacy',EntityType::class,array(
                    'class' => Pharmacy::class,
                    'choice_label'=>'namePharmacy',
                    'multiple'=>false,
                    'expanded'=>true,
                    'label'=>'choisissez une pharmacie : '))
                ->add('doctor',EntityType::class,array(
                    'class' => Doctor::class,
                    'choice_label'=>'user',
                    'multiple'=>false,
                    'expanded'=>true,
                    'label'=>'choisissez le docteur : '));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Prescription'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_prescription';
    }


}
