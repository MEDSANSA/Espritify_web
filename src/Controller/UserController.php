<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(AuthenticationUtils $authenticationUtils, UtilisateurRepository $userRepository,Security $security): Response
    {
        $u = $security->getUser();
        if(!$u) {
            return $this->redirectToRoute('app_login');
        }
        // $email = $authenticationUtils->getLastUsername();
        // $user = $userRepository->findOneByEmail($email);
        $user = $security->getUser();
        $user = $userRepository->findOneByEmail($user->getUsername());

        

        return $this->render('security/profile-etudiant.html.twig', [
            'controller_name' => 'userController',
            'user' => $user
        ]);
    }
}
