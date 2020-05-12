<?php

namespace App\UserBundle\Model;

use App\Entity\User;

interface UserManagerInterface
{
	public function createUser(): User;

	public function findUserBy(array $criteria): ?User;

	public function findUserByUsername($username): ?User;

	public function findUserByEmail($email): ?User;

	public function findUserByUsernameOrEmail($usernameOrEmail): ?User;

	public function findUserByConfirmationToken($token): ?User;

	public function updateUser(User $user): void;
}
