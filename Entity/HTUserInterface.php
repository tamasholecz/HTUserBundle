<?php

namespace HT\UserBundle\Entity;

use DateTimeInterface;
use Symfony\Component\Security\Core\User\UserInterface;

interface HTUserInterface extends UserInterface
{
    const ROLE_DEFAULT = 'ROLE_USER';

    public function setUsername(string $username);

    public function getEmail(): ?string;

    public function setEmail(string $email);

    public function getPlainPassword(): ?string;

    public function setPlainPassword(?string $password);

    public function setPassword(string $password);

    public function setEnabled(bool $boolean);

    public function getConfirmationToken(): ?string;

    public function setConfirmationToken(?string $confirmationToken);

    public function setPasswordRequestedAt(DateTimeInterface $passwordRequestedAt);

//    public function isPasswordRequestNonExpired($ttl);

    public function setLastLogin(DateTimeInterface $lastLogin);

    public function setRoles(array $roles);
}
