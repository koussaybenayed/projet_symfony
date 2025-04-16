<?php

namespace App\Security;
use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
      /** @var \App\Entity\User $user */
                $user = $token->getUser();
                $role = $user->getUserRole();

        // Assuming your user entity has a getUserRole() method
      

        if ($role === 'Admin') {
            return new RedirectResponse($this->router->generate("app_user_index"));
        }

        return new RedirectResponse($this->router->generate('app_user_profile'));
    
}
}
