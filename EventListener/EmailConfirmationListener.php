<?php

namespace App\UserBundle\EventListener;

use App\UserBundle\Entity\User;
use App\UserBundle\Event\FormEvent;
use App\UserBundle\Mailer\Mailer;
use App\UserBundle\HTUserEvents;
use App\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EmailConfirmationListener implements EventSubscriberInterface
{
	private $mailer;
	private $tokenGenerator;
	private $router;
	private $session;

	public function __construct(Mailer $mailer, TokenGeneratorInterface $tokenGenerator, UrlGeneratorInterface $router, SessionInterface $session)
	{
		$this->mailer = $mailer;
		$this->tokenGenerator = $tokenGenerator;
		$this->router = $router;
		$this->session = $session;
	}

	public static function getSubscribedEvents()
	{
		return [
			HTUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
		];
	}

	public function onRegistrationSuccess(FormEvent $event)
	{
		/** @var $user User */
		$user = $event->getForm()->getData();

		if (null === $user->getConfirmationToken()) {
			$user->setConfirmationToken($this->tokenGenerator->generateToken());
		}

		$this->mailer->sendConfirmationEmailMessage($user);
		$this->session->set('user_send_confirmation_email/email', $user->getEmail());

		$url = $this->router->generate('user_registration_check_email');
		$event->setResponse(new RedirectResponse($url));
	}
}
