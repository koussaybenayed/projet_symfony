<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) return;

        if (!$user->getisActive()) {
            throw new CustomUserMessageAuthenticationException('Your account is not active.');
        }
    }

    public function checkPostAuth(UserInterface $user): void {}
}