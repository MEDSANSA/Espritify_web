<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use App\Repository\UtilisateurRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
    private $passwordEncoder;

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private UtilisateurRepository $userRepository
    )
    //    UserPasswordEncoderInterface $passwordEncoder)
    {
       
        //    $this->passwordEncoder = $passwordEncoder;
    }

    //     public function authenticate(Request $request): Passport
    //     {
    //         $email = $request->request->get('email', '');
    // //         dd($email);
    //
    //         $request->getSession()->set(Security::LAST_USERNAME, $email);
    //
    // //         dd($request->request->get('password', ''),$email);
    //
    // //         dd($request->request->get('_csrf_token'));
    //
    //         $passport = new Passport(
    //             new UserBadge($email),
    //             new PasswordCredentials($request->request->get('password', '')),
    //             [
    //                 new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),            ]
    //         );
    //
    // //         dd($passport);
    //
    //         return $passport;
    //     }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        $password = $request->request->get('password', '');
        $csrfToken = $request->request->get('_csrf_token');



        // Validate the request parameters
        if (empty($email) || empty($password) || empty($csrfToken)) {
            // Return an error response
        }
        // Create the Passport with the UserBadge, PasswordCredentials, and CsrfTokenBadge
        $passport = new Passport(
            new UserBadge($email, function (string $userIdentifier) {
                $user = $this->userRepository->findOneBy(['email' => $userIdentifier]);
                if (!$user) {
                    //                 dd("no user");
                }


                return $user;
            }),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $csrfToken),
            ]
        );

        // Check if the Passport has a valid user
        $user = $passport->getUser();
        if (!$user) {
            // Log the error or return an error response
        }
        //         if (!$this->passwordEncoder->isPasswordValid($user, $password)) {
        //                                     dd("password not valid", $password);
        //                                 }

        // Return the Passport
        return $passport;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // For example:
        return new RedirectResponse($this->urlGenerator->generate('app_index'));
        throw new \Exception('TODO: provide a valid redirect inside ' . __FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
