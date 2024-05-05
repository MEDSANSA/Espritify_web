<?php

namespace App\Form;

use App\Entity\Club;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;



class ClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('intitule', TextType::class)
            ->add('logo', FileType::class, [
                'data_class' => null, // Indiquer que le champ n'est lié à aucune propriété de l'entité
                'data' => $options['current_logo_path'], // Définir l'URL actuelle de l'image comme valeur par défaut,
                'required' => false,
            ])
            ->add('emailClub')
            ->add('pageFb')
            ->add('pageInsta')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Club::class,
            'current_logo_path' => null,
        ]);
        $resolver->setAllowedTypes('current_logo_path', ['string', 'null']);

    }
}
