<?php

namespace HT\UserBundle\Mailer;

use HT\UserBundle\Entity\User;

interface MailerInterface
{
	public function sendConfirmationEmailMessage(User $user): void;

	public function sendResettingEmailMessage(User $user): void;
}
