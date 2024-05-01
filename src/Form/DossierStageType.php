<?php

namespace App\Form;

use App\Entity\DossierStage;
use App\Form\DataTransformer\StringToFileTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class DossierStageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cv', FileType::class, [
                'label' => 'Choose a CV',
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image (JPEG/PNG) as a CV  ',
                    ]),
                    new NotBlank([
                        'message' => 'Please upload a CV file',
                    ]),
                ],
            ])
            ->add('convention', FileType::class, [
                'label' => 'Choose an Internship convention',
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image (JPEG/PNG) as your convention',
                    ]),
                    new NotBlank([
                        'message' => 'Please upload an internship convention file',
                    ]),
                ],
            ])
            ->add('copie_cin', FileType::class, [
                'label' => 'Choose an ID CARD COPY',
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image (JPEG/PNG) as your ID Card',
                    ]),
                    new NotBlank([
                        'message' => 'Please upload an ID card copy file',
                    ]),
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Apply',
                'attr' => ['class' => 'button'],
            ]);
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DossierStage::class,
        ]);
    }
}
