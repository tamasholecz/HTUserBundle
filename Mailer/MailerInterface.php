<?php

namespace App\UserBundle\Mailer;

use App\UserBundle\Entity\User;

interface MailerInterface
{
	public function sendConfirmationEmailMessage(User $user): void;

	public function sendResettingEmailMessage(User $user): void;
}
