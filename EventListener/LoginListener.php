<?php

namespace HT\UserBundle\EventListener;

use HT\UserBundle\Entity\User;
use HT\UserBundle\Event\UserEvent;
use HT\UserBundle\HTUserEvents;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener implements EventSubscriberInterface
{
	private $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	public static function getSubscribedEvents()
	{
		return [
			SecurityEvents::INTERACTIVE_LOGIN => 'onLogin',
			HTUserEvents::SECURITY_IMPLICIT_LOGIN => 'onImplicitLogin',
		];
	}

	public function onLogin(InteractiveLoginEvent $event): void
	{
		$user = $event->getAuthenticationToken()->getUser();
		if ($user instanceof User) $this->setLastLogin($user);
	}

	public function onImplicitLogin(UserEvent $event): void
	{
		$this->setLastLogin($event->getUser());
	}

	public function setLastLogin(User $user)
	{
		$user->setLastLogin(new DateTime());
		$this->em->persist($user);
		$this->em->flush();
	}
}