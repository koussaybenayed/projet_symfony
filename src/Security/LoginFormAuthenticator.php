<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Security\UserChecker;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    private UserCheckerInterface $userChecker;

    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(
        UserCheckerInterface $userChecker,

        private RouterInterface $router,
        private HttpClientInterface $httpClient,
        private TokenStorageInterface $tokenStorage,
        private UserRepository $userRepository,
        private RequestStack $requestStack
    ) {
        $this->userChecker = $userChecker;
    }

    public function authenticate(Request $request): Passport
    {
        $username = $request->request->get('_username', '');
        $password = $request->request->get('_password', '');
        $csrfToken = $request->request->get('_csrf_token', '');
        $recaptchaToken = $request->request->get('g-recaptcha-response', '');

        if (!$this->isRecaptchaValid($recaptchaToken)) {
            throw new CustomUserMessageAuthenticationException('Please confirm you are not a robot.');
        }

        return new Passport(
            new UserBadge($username, function ($userIdentifier) {
                $user = $this->userRepository->findOneBy(['user_username' => $userIdentifier]);

                if (!$user) {
                    throw new CustomUserMessageAuthenticationException('Username could not be found.');
                }

                // Manually check user status
                $this->userChecker->checkPreAuth($user);

                return $user;
            }),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $csrfToken),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        /** @var User $user */
        $user = $token->getUser();

        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        if ($user->getUserRole() === 'Admin') {
            return new RedirectResponse($this->router->generate("app_user_index"));
        }

        return new RedirectResponse($this->router->generate('app_user_profile'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $session = $this->requestStack->getSession();
    
        if ($session && !$session->isStarted()) {
            $session->start();
        }
    
        // Add the flash message for authentication failure
        $session->getFlashBag()->add('error', 'Your account is not active.');

        return new RedirectResponse($this->router->generate(self::LOGIN_ROUTE));
    }
    
    
    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate(self::LOGIN_ROUTE);
    }

    private function isRecaptchaValid(string $token): bool
    {
        if (empty($token)) return false;

        $response = $this->httpClient->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [
            'body' => [
                'secret' => '6Lfyyh8rAAAAAFdchRu0Z4g_O-DLDG6lopyVRoS7',
                'response' => $token,
            ],
        ]);

        $data = $response->toArray();
        return $data['success'] ?? false;
    }
}
