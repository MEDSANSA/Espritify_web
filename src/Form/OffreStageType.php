<?php
// src/Form/OffreStageType.php

namespace App\Form;

use App\Entity\Offrestage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\NotBlank;

class OffreStageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Internship title',
                'attr' => ['class' => 'form-control'],
                'required' => false,
                
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'choices' => [
                    'Remote' => 'remote',
                    'Presentiel' => 'presentiel',
                ],
                'attr' => ['class' => 'form-select'],
                'required' => false,
            ])
            ->add('competance', TextType::class, [
                'label' => 'Skills',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('nom_soc', TextType::class, [
                'label' => 'Company name',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('desc_soc', TextType::class, [
                'label' => 'Description of the company',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save Changes',
                'attr' => ['class' => 'btn bg-gradient-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offrestage::class,
        ]);
    }
}
