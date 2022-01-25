<?php

declare(strict_types=1);

namespace App\Security\Recruiter;

use App\Entity\Recruiter\Recruiter;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class RecruiterChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof Recruiter) {
            return;
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof Recruiter) {
            return;
        }
        if (false === $user->getIsVerified()) {
            throw new UnsupportedUserException('Veuillez vérifier votre email');
        }
    }
}
