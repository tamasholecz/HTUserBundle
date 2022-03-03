<?php

namespace HT\UserBundle\Mailer;

use HT\UserBundle\Entity\HTUserInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserMailer implements UserMailerInterface
{
    protected $mailer;

    protected $router;

    protected $translator;

    protected $mailerAddress;
    protected $mailerName;

    public function __construct(MailerInterface $mailer, UrlGeneratorInterface $router, TranslatorInterface $translator, ParameterBagInterface $parameterBag)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->translator = $translator;
        $this->mailerAddress = $parameterBag->has('mailer_sender') ? $parameterBag->get('mailer_sender') : 'test@test.com';
        $this->mailerName = $parameterBag->has('mailer_sender_name') ? $parameterBag->get('mailer_sender_name') : '';
    }

    public function sendConfirmationEmailMessage(HTUserInterface $user): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->mailerAddress, $this->mailerName))
            ->to(new Address($user->getEmail()))
            ->subject($this->translator->trans('Registration email subject', ['%user%' => $user], 'user'))
            ->htmlTemplate('@HTUser/registration_email.html.twig')
            ->context([
                'confirmationUrl' => $this->router->generate('user_registration_confirm', ['token' => $user->getConfirmationToken()], UrlGeneratorInterface::ABSOLUTE_URL),
                'user' => $user,
            ]);
        $this->mailer->send($email);
    }

    public function sendResettingEmailMessage(HTUserInterface $user): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->mailerAddress, $this->mailerName))
            ->to(new Address($user->getEmail()))
            ->subject($this->translator->trans('Resetting email subject', ['%user%' => $user], 'user'))
            ->htmlTemplate('@HTUser/resetting_email.html.twig')
            ->context([
                'confirmationUrl' => $this->router->generate('user_resetting_reset', ['token' => $user->getConfirmationToken()], UrlGeneratorInterface::ABSOLUTE_URL),
                'user' => $user,
            ]);
        $this->mailer->send($email);
    }
}
