<?php

namespace HT\UserBundle\Model;

use App\Entity\User;

abstract class UserManager implements UserManagerInterface
{
	public function createUser(): User
	{
		return new User();
	}

	public function findUserByEmail($email): ?User
	{
		return $this->findUserBy(['email' => $email]);
	}

	public function findUserByUsername($username): ?User
	{
		return $this->findUserBy(['username' => $username]);
	}

	public function findUserByUsernameOrEmail($usernameOrEmail): ?User
	{
		if (preg_match('/^.+\@\S+\.\S+$/', $usernameOrEmail)) {
			$user = $this->findUserByEmail($usernameOrEmail);
			if (null !== $user) {
				return $user;
			}
		}

		return $this->findUserByUsername($usernameOrEmail);
	}

	public function findUserByConfirmationToken($token): ?User
	{
		return $this->findUserBy(['confirmationToken' => $token]);
	}
}
