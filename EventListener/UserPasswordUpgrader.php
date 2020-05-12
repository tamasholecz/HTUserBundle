<?php

namespace HT\UserBundle\EventListener;

use HT\UserBundle\Entity\User;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserPasswordUpgrader
{
	private $passwordEncoder;

	public function __construct(UserPasswordEncoderInterface $passwordEncoder)
	{
		$this->passwordEncoder = $passwordEncoder;
	}

	public function prePersist(User $user, LifecycleEventArgs $event)
	{
		$this->updatePassword($user);
	}

	public function preUpdate(User $user, PreUpdateEventArgs $event)
	{
		$this->updatePassword($user);
	}

	public function updatePassword(User $user): void
	{
		if (0 === strlen($user->getPlainPassword())) {
			return;
		}

		$user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPlainPassword()));
		$user->eraseCredentials();
	}
}