<?php
namespace App\Security;

use App\Entity\User as AppUser;

use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

// user is deleted, show a generic Account Not Found message.
        if ($user->getIsActive() == false || $user->getIsActive() == null ) {
            throw new DisabledException('User account is Disabled');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }


    }
}