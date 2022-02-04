<?php

declare(strict_types=1);

namespace App\Security\Candidate;

use App\Entity\Candidate;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class CandidateChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof \App\Entity\Candidate) {
            return;
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof Candidate) {
            return;
        }
        if (false === $user->getIsVerified()) {
            throw new UnsupportedUserException('Veuillez vérifier votre email');
        }
    }
}
