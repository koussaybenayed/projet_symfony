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
use Symfony\Component\Security\Core\Security;
use Psr\Log\LoggerInterface;

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
        private RequestStack $requestStack,
        private LoggerInterface $logger
    ) {
        $this->userChecker = $userChecker;
    }

    public function authenticate(Request $request): Passport
    {
        $username = $request->request->get('user_username', '');
        $password = $request->request->get('user_password', '');
        $csrfToken = $request->request->get('_csrf_token', '');
        $recaptchaToken = $request->request->get('g-recaptcha-response', '');

        $this->logger->info('Authentication attempt', [
            'username' => $username,
            'has_password' => !empty($password),
            'has_csrf' => !empty($csrfToken),
            'has_recaptcha' => !empty($recaptchaToken)
        ]);

        if (!$this->isRecaptchaValid($recaptchaToken)) {
            $this->logger->warning('Recaptcha validation failed');
            throw new CustomUserMessageAuthenticationException('Please confirm you are not a robot.');
        }

        $request->getSession()->set(Security::LAST_USERNAME, $username);

        return new Passport(
            new UserBadge($username, function ($userIdentifier) {
                $this->logger->info('Looking up user', ['username' => $userIdentifier]);
                $user = $this->userRepository->findOneBy(['user_username' => $userIdentifier]);

                if (!$user) {
                    $this->logger->warning('User not found', ['username' => $userIdentifier]);
                    throw new CustomUserMessageAuthenticationException('Username could not be found.');
                }

                $this->logger->info('User found', [
                    'username' => $user->getUserUsername(),
                    'is_active' => $user->getisActive(),
                    'has_password' => !empty($user->getUserPassword())
                ]);

                if (!$user->getisActive()) {
                    $this->logger->warning('Inactive user attempt', ['username' => $userIdentifier]);
                    throw new CustomUserMessageAuthenticationException('Your account is not active.');
                }

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
        $this->logger->info('Authentication successful', [
            'username' => $token->getUserIdentifier()
        ]);

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
        $this->logger->error('Authentication failed', [
            'exception' => $exception->getMessage(),
            'username' => $request->request->get('user_username', '')
        ]);

        if ($request->hasSession()) {
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        }

        $url = $this->getLoginUrl($request);

        return new RedirectResponse($url);
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
