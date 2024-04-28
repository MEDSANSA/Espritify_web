<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Repository\UtilisateurRepository;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{




           #[Route('/signup', name: 'signup')]
           public function signup(Request $request,
           UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, EntityManagerInterface $entityManager, UtilisateurRepository $userRepository): Response
           {
            $user = new Utilisateur();
                   $form = $this->createForm(RegistrationFormType::class, $user);
                   $form->handleRequest($request);
                   if ($form->isSubmitted() ) {
                    if ($user->getEmail()!= null){
                        $emailexist = $userRepository->findOneByEmail($user->getEmail());
                        if($emailexist) {
                            return $this->render('security/new.html.twig', ['error' => "Email already exist",
                            'form' => $form->createView(),]);
                        }

                    }
                   
                    
                    
                    if($user->getNom() == null || $user->getPrenom() ==null || $user->getEmail() == null || $user->getMdp() ==null
                    || $user->getTel() == null ||$user->getRole() == "") {
                        // dd($user);
                        return $this->render('security/new.html.twig', ['error' => "invalid data",
                        'form' => $form->createView(),]);
                    }

                    if(strlen($user->getTel())!= 8) {
                        // dd($user);
                        return $this->render('security/new.html.twig', ['error' => "phone must be = 8",
                        'form' => $form->createView(),]);
                    }
                               // encode the plain password
                   //             dd($form->get('mdp')->getData());
                               $user->setPassword(
                                   $userPasswordHasher->hashPassword(
                                       $user,
                                       $form->get('mdp')->getData()
                                   )
                               );

                               $entityManager->persist($user);
                               $entityManager->flush();
                               // do anything else you need here, like send an email

                               return $this->redirectToRoute('app_profile');
                           }
               return $this->render('security/new.html.twig', [
                'error' => "",
                   'controller_name' => 'accueilController',
                   'form' => $form->createView(),
               ]);
           }

    #[Route('/email', name: 'email_app')]
    public function email(MailerInterface $mailer): Response
    {

        $emailobj = (new Email())
            ->from('wajdibejaoui26@gmail.com')
            ->to("wajdibejaoui50@gmail.com")
            ->subject('Reset Password !')
            ->text("wawa")
            ->html('<p>' . "wawa" . '</p>'); // Set HTML version

        try {
            $mailer->send($emailobj);
            // Handle successful email sending (e.g., redirect to a success page)
        } catch (TransportExceptionInterface $e) {
            // Handle email sending failure (e.g., log the error, display an error message to the user)
            // You can access the error message using $e->getMessage()
            dd($e);
        }
//        dd($test);
        return $this->redirectToRoute('app_login');
    }
}