<?php

namespace HT\UserBundle\EventListener;

use HT\UserBundle\Event\UserNullableEvent;
use HT\UserBundle\HTUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ResettingListener implements EventSubscriberInterface
{
	private $router;

	public function __construct(UrlGeneratorInterface $router)
	{
		$this->router = $router;
	}

	/**
	 * @return array
	 */
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
