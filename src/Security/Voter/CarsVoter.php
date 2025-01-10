<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

final class CarsVoter extends Voter
{
    public const EDIT = 'POST_EDIT';
    public const VIEW = 'POST_VIEW';
    public const DELETE = 'POST_DELETE';

    public function __construct(
        private AccessDecisionManagerInterface $accessDecisionManager) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof \App\Entity\Cars;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if ($this->accessDecisionManager->decide($token, ['ROLE_ADMIN'])) {
            return true;
        }
        if ($this->accessDecisionManager->decide($token, ['ROLE_SUPER_ADMIN'])) {
            return true;
        }
        //die('attribute');
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
            //dump($user);
            //dd($subject->getUser());
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
            case self::DELETE:
                // logic to determine if the user can EDIT
                // return true or false
                return $subject->getUser() === $user;
                break;

            case self::VIEW:
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }

}
