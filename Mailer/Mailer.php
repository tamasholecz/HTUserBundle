<?php

namespace HT\UserBundle\Mailer;

use HT\UserBundle\Entity\HTUserInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Mailer implements MailerInterface
{
	protected $mailer;

	protected $router;

	public function __construct(TwigSwiftMailer $mailer, UrlGeneratorInterface $router)
	{
		$this->mailer = $mailer;
		$this->router = $router;
	}

	public function sendConfirmationEmailMessage(HTUserInterface $user): void
	{
		$confirmationUrl = $this->router->generate('user_registration_confirm', ['token' => $user->getConfirmationToken()], UrlGeneratorInterface::ABSOLUTE_URL);
		$this->mailer->send('@HTUser/registration_email.html.twig', ['user' => $user, 'confirmationUrl' => $confirmationUrl], $user->getEmail());
	}

	public function sendResettingEmailMessage(HTUserInterface $user): void
	{
		$confirmationUrl = $this->router->generate('user_resetting_reset', ['token' => $user->getConfirmationToken()], UrlGeneratorInterface::ABSOLUTE_URL);
		$this->mailer->send('@HTUser/resetting_email.html.twig', ['user' => $user, 'confirmationUrl' => $confirmationUrl], $user->getEmail());
	}
}
