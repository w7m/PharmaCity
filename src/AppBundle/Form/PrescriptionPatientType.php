<?php

namespace AppBundle\Form;

use AppBundle\Entity\Patient;
use AppBundle\Entity\Pharmacy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PrescriptionPatientType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('patient')
            ->remove('Envoyer')
                ->add('pharmacy',EntityType::class,array(
                    'class' => Pharmacy::class,
                    'choice_label'=>'namePharmacy',
                    'multiple'=>false,
                    'label'=>'choisissez une pharmacy : '))
                ->add('Envoyer',SubmitType::class);

    }/**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return PrescriptionType::class;
    }


}
