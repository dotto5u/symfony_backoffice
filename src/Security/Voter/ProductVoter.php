<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class ProductVoter extends Voter
{   
    public const INDEX = 'PRODUCT_INDEX';
    public const NEW = 'PRODUCT_NEW';
    public const SHOW = 'PRODUCT_SHOW';
    public const EDIT = 'PRODUCT_EDIT';
    public const DELETE = 'PRODUCT_DELETE';
    public const EXPORT = 'PRODUCT_EXPORT';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [
            self::INDEX, 
            self::NEW, 
            self::SHOW, 
            self::EDIT, 
            self::DELETE, 
            self::EXPORT
        ]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        return match ($attribute) {
            self::INDEX, self::SHOW, self::EXPORT => true,
            self::NEW, self::EDIT, self::DELETE => in_array('ROLE_ADMIN', $user->getRoles(), true),
            default => false,
        };
    }
}
