<?php

namespace App\Form;

use App\Entity\Entretien;
use App\Entity\Offrestage;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntretienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', TextType::class, [
                'label' => 'Type',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('date', DateType::class, [
                'label' => 'Date',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('lieu', TextType::class, [
                'label' => 'Place',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('etat', TextType::class, [
                'label' => 'State',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('id_user', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => function (Utilisateur $user) {
                    return $user->getNom() . ' ' . $user->getPrenom();
                },
                'label' => 'User',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('id_stage', EntityType::class, [
                'class' => Offrestage::class,
                'choice_label' => function (Offrestage $offrestage) {
                    return $offrestage->getTitre() ;
                },
                'label' => 'Offre stage',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save Changes',
                'attr' => ['class' => 'btn bg-gradient-primary'],
            ]);
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entretien::class,
        ]);
    }
}
