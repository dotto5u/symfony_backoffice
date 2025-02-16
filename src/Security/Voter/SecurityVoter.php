<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class SecurityVoter extends Voter
{
    public const REGISTER = 'REGISTER';
    public const LOGIN = 'LOGIN';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [
            self::REGISTER, 
            self::LOGIN
        ]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        return !$user instanceof UserInterface;
    }
}
