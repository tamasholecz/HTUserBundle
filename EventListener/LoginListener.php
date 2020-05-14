<?php

namespace HT\UserBundle\EventListener;

use DateTime;
use HT\UserBundle\Entity\HTUserInterface;
use HT\UserBundle\Event\UserEvent;
use HT\UserBundle\HTUserEvents;
use HT\UserBundle\Model\UserManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener implements EventSubscriberInterface
{
	private $userManager;

	public function __construct(UserManagerInterface $userManager)
	{
		$this->userManager = $userManager;
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
		if ($user instanceof HTUserInterface) $this->setLastLogin($user);
	}

	public function onImplicitLogin(UserEvent $event): void
	{
		$this->setLastLogin($event->getUser());
	}

	public function setLastLogin(HTUserInterface $user)
	{
		$user->setLastLogin(new DateTime());
		$this->userManager->updateUser($user);
	}
}