<?php

namespace HT\UserBundle\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use HT\UserBundle\Entity\HTUserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserPasswordUpgrader
{
	private $passwordEncoder;

	public function __construct(UserPasswordEncoderInterface $passwordEncoder)
	{
		$this->passwordEncoder = $passwordEncoder;
	}

	public function prePersist(HTUserInterface $user, LifecycleEventArgs $event)
	{
		$this->updatePassword($user);
	}

	public function preUpdate(HTUserInterface $user, PreUpdateEventArgs $event)
	{
		$this->updatePassword($user);
	}

	public function updatePassword(HTUserInterface $user): void
	{
		if (0 === strlen($user->getPlainPassword())) {
			return;
		}

		$user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPlainPassword()));
		$user->eraseCredentials();
	}
}