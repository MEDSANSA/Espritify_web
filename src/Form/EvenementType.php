<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\Club;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType; // Importer le type TextType



class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [])
            ->add('description')
            ->add('lieu', TextType::class, [
                'label' => 'Lieu de l\'événement',
            ])
            ->add('date_debut', DateType::class, [
                'widget' => 'choice',
            ])
            ->add('date_fin')
            ->add(
                'id_club',
                EntityType::class,
                ['class' => Club::class, 'choice_label' => 'intitule']
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
