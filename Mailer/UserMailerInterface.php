<?php

namespace HT\UserBundle\Mailer;

use HT\UserBundle\Entity\HTUserInterface;

interface UserMailerInterface
{
	public function sendConfirmationEmailMessage(HTUserInterface $user): void;

	public function sendResettingEmailMessage(HTUserInterface $user): void;
}
