<?php

namespace HT\UserBundle\Doctrine;

use App\Entity\User;
use HT\UserBundle\Model\UserManager as BaseUserManager;
use Doctrine\ORM\EntityManagerInterface;

class UserManager extends BaseUserManager
{
	protected $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	public function findUserBy(array $criteria): ?User
	{
		return $this->getRepository()->findOneBy($criteria);
	}

	protected function getRepository()
	{
		return $this->em->getRepository(User::class);
	}

	public function updateUser(User $user, $andFlush = true): void
	{
		$this->em->persist($user);
		if ($andFlush) {
			$this->em->flush();
		}
	}
}
