<?php

namespace App\UserBundle\EventListener;

use App\UserBundle\Event\UserEvent;
use App\UserBundle\Security\LoginFormAuthenticator;
use App\UserBundle\HTUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class AuthenticationListener implements EventSubscriberInterface
{
	private $authenticatorHandler;
	private $loginFormAuthenticator;
	private $eventDispatcher;
	private $firewallName;

	public function __construct(GuardAuthenticatorHandler $authenticatorHandler, LoginFormAuthenticator $loginFormAuthenticator, EventDispatcherInterface $eventDispatcher, string $firewallName = 'main')
	{
		$this->authenticatorHandler = $authenticatorHandler;
		$this->loginFormAuthenticator = $loginFormAuthenticator;
		$this->eventDispatcher = $eventDispatcher;
		$this->firewallName = $firewallName;
	}

	public static function getSubscribedEvents()
	{
		return [
			HTUserEvents::REGISTRATION_COMPLETED => 'authenticate',
			HTUserEvents::REGISTRATION_CONFIRMED => 'authenticate',
			HTUserEvents::RESETTING_RESET_COMPLETED => 'authenticate',
		];
	}

	public function authenticate(UserEvent $event)
	{
		if (!$event->getUser()->getEnabled()) return;
		try {
			$this->authenticatorHandler->authenticateUserAndHandleSuccess($event->getUser(), $event->getRequest(), $this->loginFormAuthenticator, $this->firewallName);

			$this->eventDispatcher->dispatch($event, HTUserEvents::SECURITY_IMPLICIT_LOGIN);
		} catch (AccountStatusException $ex) {
			// We simply do not authenticate users which do not pass the user
			// checker (not enabled, expired, etc.).
		}
	}
}
