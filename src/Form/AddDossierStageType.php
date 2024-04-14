<?php

namespace App\Form;

use App\Entity\DossierStage;
use App\Entity\Offrestage;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddDossierStageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cv', FileType::class,  [
                'label' => 'Student CV',
                'attr' => ['class' => 'form-control'],
                'data_class' => null,
                
            ])
            ->add('convention', Filetype::class,  [
                'label' => 'Internship folder Convention',
                'attr' => ['class' => 'form-control'],
                'data_class' => null,
            ])
            ->add('copie_cin' , FileType::class,  [
                'label' => 'ID Card Copy',
                'attr' => ['class' => 'form-control'],
                'data_class' => null,
            ])
            ->add('id_user', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => function (Utilisateur $user) {
                    return $user->getNom() . ' ' . $user->getPrenom();
                },
                'label' => 'User',
                'attr' => ['class' => 'form-select'],
            ])
            ->add('id_offre', EntityType::class, [
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
            'data_class' => DossierStage::class,
        ]);
    }
}
