<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\FileTransport;
use App\Form\RegistrationFormType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\ResetPasswordFormType;
use Symfony\Component\Security\Core\Security;

class SecurityController extends AbstractController
{
    #[Route('/signin', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Security $security, UtilisateurRepository $userRepository): Response
    {
        /*if ($this->getUser()) {
            return $this->redirectToRoute('app_profile');
        }*/

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        //         if ($error)
        //         dd($error);

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($security->isGranted('IS_AUTHENTICATED_FULLY')) {

            return $this->render('base.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
        } else {
            // Return the existing response when the user is not authenticated
            return $this->render('security/sign-in.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
        }
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): Response
    {
        return $this->redirectToRoute('app_login');
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/forgotten', name: 'forgotten_password')]
    public function forgottenPassword(
        Request $request,
        UtilisateurRepository $usersRepository,
        TokenGeneratorInterface $tokenGenerator,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ): Response {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();

            $user = $usersRepository->findOneByEmail($form->get('email')->getData());



            if ($user) {
                $token = $tokenGenerator->generateToken();
                $user->setResetToken($token);

                $entityManager->persist($user);
                $entityManager->flush();

                $url = $this->generateUrl('reset_pass', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);


                $emailBody = "To reset your password, please visit the following link: $url";


                $context = compact('url', 'user');
                $emailobj = (new Email())
                    ->from('noreply@gmail.com')
                    ->to($email)
                    ->subject('Reset Password !')
                    ->text($emailBody)
                    ->html('<p>' . $emailBody . '</p>'); // Set HTML version

                $mailer->send($emailobj);
                //                    dd($email);
                return $this->render('security/reset_password_delivered.html.twig');
                //                        return $this->redirectToRoute('app_login');
                //                    $email->dump(); // This will output the email content to the profiler
                //                    var_dump($email);
                // Specify the relative path to the current directory
                //                    $relativePath = __DIR__ . '/emails';
                //
                //// Create the FileTransport with the specified path
                //                    $transport = new FileTransport('file://' . $relativePath);

                //                    $mailer->send($email, $transport);

            }
            $this->addFlash('danger', 'Un problème est survenu');
            //                return $this->redirectToRoute('app_login');

        }

        return $this->render('security/oblier_password.html.twig', [
            'requestPassForm' => $form->createView()
        ]);
    }
    #[Route('/forgotten/{token}', name: 'reset_pass')]
    public function resetPass(
        string $token,
        Request $request,
        UtilisateurRepository $usersRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        // On vérifie si on a ce token dans la base
        $user = $usersRepository->findOneByResetToken($token);

        if ($user) {
            $form = $this->createForm(ResetPasswordFormType::class);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // On efface le token
                $user->setResetToken('');
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('mdp')->getData()
                    )
                );
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Password updated succefully !');
                return $this->redirectToRoute('app_login');
            }

            return $this->render('security/reset_password.html.twig', [
                'passForm' => $form->createView()
            ]);
        }
        $this->addFlash('danger', 'Jeton invalide');
        return $this->redirectToRoute('app_login');
    }
    #[Route('/users', name: 'app-users')]
    public function affichage(UtilisateurRepository $user, Security $security): Response
    {

        $u = $security->getUser();
        if (!$u) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/tables.html.twig', [
            'users' => $user->findAll(),
        ]);
    }
    #[Route('/index', name: 'app_index')]
    public function afterlogin(Security $security, UtilisateurRepository $userRepository): Response
    {
        $u = $security->getUser();
        $email = $u->getUsername();
        $datauser = $userRepository->findOneByEmail($email);
        if ($datauser->getRole() == 'admin') {
            return $this->redirectToRoute('app_admin_accueil');
        }

        
            return $this->redirectToRoute('app_profile');
    }
    #[Route('/edit-user/{id}', name: 'app-edit')]
    public function update($id, UtilisateurRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher, Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $u = $security->getUser();
        if (!$u) {
            return $this->redirectToRoute('app_login');
        }
        $user = $userRepository->find($id);
        // dd($user);

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $emailexist = $userRepository->findOneByEmail($user->getEmail());


            if (
                $user->getNom() == null || $user->getPrenom() == null || $user->getEmail() == null || $user->getMdp() == null
                || $user->getTel() == null || $user->getRole() == null || $user->getMdp() == ""
            ) {
                // dd($user);
                return $this->render('security/new.html.twig', [
                    'error' => "invalid data",
                    'form' => $form->createView(),
                ]);
            }

            if (strlen($user->getTel()) != 8) {
                // dd($user);
                return $this->render('security/new.html.twig', [
                    'error' => "phone must be = 8",
                    'form' => $form->createView(),
                ]);
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
            $u = $security->getUser();
            $email = $u->getUsername();
            $datauser = $userRepository->findOneByEmail($email);
            if ($datauser->getRole() == 'admin') {
                return $this->redirectToRoute('app-users');
            }


            return $this->redirectToRoute('app_profile');
        }


        return $this->render('security/edit-profile.html.twig', [
            'form' => $form->createView(),
            'error' => ""
        ]);
    }

    #[Route('/delete/{id}', name: 'app-delete-user')]
    public function deleteUser(EntityManagerInterface $entityManager, UtilisateurRepository $userRepository, $id, Security $security)
    {
        $u = $security->getUser();
        if (!$u) {
            return $this->redirectToRoute('app_login');
        }

        // Retrieve the user entity from the repository
        $user = $userRepository->find($id);

        // Check if the user exists
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        // Remove the user entity from the EntityManager
        $entityManager->remove($user);

        // Flush the changes to the database
        $entityManager->flush();

        // Optionally, redirect to another route or return a response
        return $this->redirectToRoute('app-users');
    }
    #[Route('/', name: 'app_searsh', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $searchTerm = $request->query->get('q');
        $user = null;
        if (!$searchTerm) {
            $quizzs = $entityManager
                ->getRepository(Utilisateur::class)
                ->findAll();
        } else {
            $user = $entityManager
                ->getRepository(Utilisateur::class)
                ->createQueryBuilder('q')
                ->where('q.nom LIKE :searchTerm')
                ->setParameter('searchTerm', '%' . $searchTerm . '%')
                ->getQuery()
                ->getResult();
        }

        return $this->render('security/tables.html.twig', [
            'users' => $user,
            /*'quizzs' => $quizzs,*/
            'searchTerm' => $searchTerm,

        ]);

        /* $users = $this->getDoctrine()->getRepository(Utilisateur::class)->findAll();

    return $this->render('tables.html.twig', [
        'users' => $users,
    ]);*/
    }

    #[Route('/admin/getUserByRole/{role}', name: 'app_admin_fetch_role')]
    public function getDossierByOffreId($role, UtilisateurRepository  $userRepository): Response
    {

        if ($role == "all") {
            return $this->render('security/tables.html.twig', [
                'users' => $userRepository->findAll(),
            ]);
        }
        $users = $userRepository->findBy(['role' => $role]);
        //

        return $this->render('security/tables.html.twig', [
            'users' => $users,
        ]);
    }
    #[Route('/security/tables', name: 'app_admin_sort')]
    public function trie(EntityManagerInterface $entityManager, Request $request, UtilisateurRepository $UtilisiteurRepository): Response
    {
        $user = $UtilisiteurRepository->findAll();



        usort($user, function ($a, $b) {
            return $a->getNom() <=> $b->getNom();
        });




        return $this->render('security/tables.html.twig', [
            'users' => $user
        ]);
    }



    #[Route('/admin-profile', name: 'app_admin_profile')]
    public function adminProfile(UtilisateurRepository $userRepository,Security $security): Response
    {
        $u = $security->getUser();
        if(!$u) {
            return $this->redirectToRoute('app_login');
        }
        // $email = $authenticationUtils->getLastUsername();
        // $user = $userRepository->findOneByEmail($email);
        $user = $userRepository->findOneByEmail($u->getUsername());

        

        return $this->render('security/admin-profile.html.twig', [
            'user' => $user
        ]);
    }
}
