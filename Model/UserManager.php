<?php

namespace HT\UserBundle\Model;

use HT\UserBundle\Entity\HTUserInterface;

abstract class UserManager implements UserManagerInterface
{
	protected $userClass;

	public function createUser(): HTUserInterface
	{
		return new $this->userClass();
	}

	public function findUserByEmail($email): ?HTUserInterface
	{
		return $this->findUserBy(['email' => $email]);
	}

	public function findUserByUsername($username): ?HTUserInterface
	{
		return $this->findUserBy(['username' => $username]);
	}

	public function findUserByUsernameOrEmail($usernameOrEmail): ?HTUserInterface
	{
		if (preg_match('/^.+\@\S+\.\S+$/', $usernameOrEmail)) {
			$user = $this->findUserByEmail($usernameOrEmail);
			if (null !== $user) {
				return $user;
			}
		}

		return $this->findUserByUsername($usernameOrEmail);
	}

	public function findUserByConfirmationToken($token): ?HTUserInterface
	{
		return $this->findUserBy(['confirmationToken' => $token]);
	}

	public function getUserClass(): string
	{
		return $this->userClass;
	}
}
