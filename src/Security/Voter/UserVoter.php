<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserVoter extends Voter
{
    public const INDEX = 'USER_INDEX';
    public const NEW = 'USER_NEW';
    public const SHOW = 'USER_SHOW';
    public const EDIT = 'USER_EDIT';
    public const DELETE = 'USER_DELETE';

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

        return in_array('ROLE_ADMIN', $user->getRoles(), true);
    }
}
