<?php

namespace App\Security\Voter;

use App\Entity\Candidature;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class CandidatureVoter extends Voter
{
    public const VIEW = 'CANDIDATURE_VIEW';
    public const EDIT = 'CANDIDATURE_EDIT';
    public const DELETE = 'CANDIDATURE_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE], true)
            && $subject instanceof Candidature;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Candidature $candidature */
        $candidature = $subject;

        return  $candidature->getUser() === $user;
        }
    }