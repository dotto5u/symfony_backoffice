<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class ClientVoter extends Voter
{   
    public const INDEX = 'CLIENT_INDEX';
    public const NEW = 'CLIENT_NEW';
    public const SHOW = 'CLIENT_SHOW';
    public const EDIT = 'CLIENT_EDIT';
    public const DELETE = 'CLIENT_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [
            self::INDEX, 
            self::NEW, 
            self::SHOW, 
            self::EDIT, 
            self::DELETE
        ]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        $roles = $user->getRoles();

        return in_array('ROLE_ADMIN', $roles, true) || in_array('ROLE_MANAGER', $roles, true);
    }
}
