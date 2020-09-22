<?php

namespace HT\UserBundle\EventListener;

use HT\UserBundle\Entity\HTUserInterface;
use HT\UserBundle\Event\FormEvent;
use HT\UserBundle\Event\UserNullableEvent;
use HT\UserBundle\Mailer\UserMailer;
use HT\UserBundle\HTUserEvents;
use HT\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ResettingListener implements EventSubscriberInterface
{
	private $mailer;
	private $tokenGenerator;
	private $router;
	private $session;

	public function __construct(UrlGeneratorInterface $router)
	{
		$this->router = $router;
	}

	public static function getSubscribedEvents()
	{
		return [
			HTUserEvents::RESETTING_SEND_EMAIL_INITIALIZE => 'resettingInitilize',
		];
	}

	public function resettingInitilize(UserNullableEvent $event)
	{
		if (null === $event->getUser()) {
			$url = $this->router->generate('user_resetting_request');
			$event->setResponse(new RedirectResponse($url));
		}
	}
}
