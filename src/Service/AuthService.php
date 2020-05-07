<?php


namespace Juff\Service;

use Juff\Entity\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AuthService
{
    public static function authorizeUser(SessionInterface $session): void
    {
        $session->set('role', User\Roles::ADMIN);
    }

    public static function createUserFromSession(SessionInterface $session): User
    {
        $role = $session->get('role');
        if ($role && $role === User\Roles::ADMIN) {
            $user = new User(User\Roles::ADMIN);
            $user->grantPermission(User\Permissions::EDIT);
            $user->grantPermission(User\Permissions::LOGOUT);
        } else {
            $user = new User(User\Roles::ANON);
        }

        return $user;
    }

    public static function logOut(SessionInterface $session)
    {
        $session->remove('role');
    }
}