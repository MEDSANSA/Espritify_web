<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', TextType::class, [
                'label' => 'Name',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Name',
                    'aria-label' => 'Name',
                    'aria-describedby' => 'email-addon',
                    'required' => false,
                    'empty_data' => null, // Ensure null value is preserved
                ],
            ])
            ->add('nom', TextType::class, [
                'label' => 'last name',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Last Name',
                    'aria-label' => 'Name',
                    'aria-describedby' => 'email-addon',
                    'required' => false,
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Email',
                    'aria-label' => 'Email',
                    'aria-describedby' => 'email-addon',
                    'required' => false,
                ],
            ])
            ->add('tel', TextType::class, [
                'label' => 'Telephone',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Telephone',
                    'aria-label' => 'Telephone',
                    'aria-describedby' => 'email-addon',
                    'required' => false,
                ],
            ])
            ->add('role', ChoiceType::class, [
                'label' => 'Role',
                'choices' => [
                    'etudiant' => 'etudiant', // Replace 'option_a_value' with your actual value
                    'enseignant' => 'enseignant', // Replace 'option_b_value' with your actual value
                    'responsable societe' => 'responsable_societe', // Replace 'option_b_value' with your actual value
                    // Add more options as needed
                ],
                'placeholder' => 'Choose an option', // Optional: Add a placeholder
                'required' => false,
                // Other options...
            ])
            ->add('mdp', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'first_options' => [
                    'label' => 'Password',
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Password',
                        'aria-label' => 'Password',
                        'aria-describedby' => 'password-addon',
                    ],
                    'required' => false,
                ],
                'second_options' => [
                    'label' => 'repeat Password',
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'repeat Password',
                        'aria-label' => 'Password',
                        'aria-describedby' => 'password-addon',
                    ],
                    'required' => false,
                ],
                'constraints' => [
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/',
                        'message' => 'Your password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number.',
                    ]),

                ],
                'options' => ['attr' => ['class' => 'form-control']],
            ])
              ->add('submit', SubmitType::class, [
                            'label' => 'Sign up',
                            'attr' => [
                                'class' => 'btn bg-gradient-dark w-100 my-4 mb-2',
                            ],
                        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,

        ]);
    }
}
