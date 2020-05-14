<?php

namespace HT\UserBundle\Model;

use HT\UserBundle\Entity\HTUserInterface;

interface UserManagerInterface
{
	public function createUser(): HTUserInterface;

	public function findUserBy(array $criteria): ?HTUserInterface;

	public function findUserByUsername($username): ?HTUserInterface;

	public function findUserByEmail($email): ?HTUserInterface;

	public function findUserByUsernameOrEmail($usernameOrEmail): ?HTUserInterface;

	public function findUserByConfirmationToken($token): ?HTUserInterface;

	public function updateUser(HTUserInterface $user): void;
}
