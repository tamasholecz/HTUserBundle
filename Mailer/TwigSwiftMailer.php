<?php

namespace App\UserBundle\Mailer;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Environment;

class TwigSwiftMailer
{
	private $twig;
	private $parameterBag;
	private $mailer;
	protected $embeds;// [twig name => file path]
	protected $subject, $bodyHtml;

	public function __construct(Environment $twig, ParameterBagInterface $parameterBag, \Swift_Mailer $mailer)
	{
		$this->twig = $twig;
		$this->parameterBag = $parameterBag;
		$this->mailer = $mailer;
	}

	public function getMessage($twigname, array $parameters, $toEmail, $fromEmail = null)
	{
		if ($fromEmail === null) $fromEmail = $this->parameterBag->get('mailer_address');
		$message = new \Swift_Message();
		if (isset($this->embeds)) foreach($this->embeds as $par => $path) $parameters[$par] = $message->embed(\Swift_Image::fromPath($path));
		$template = $this->twig->load($twigname);
		$parameters = $this->twig->mergeGlobals($parameters);
		$this->subject = $template->renderBlock('subject', $parameters);
		$this->bodyHtml = $template->renderBlock('body_html', $parameters);
//		$bodyText = $template->renderBlock('body_text', $parameters);

		$message
			->setSubject($this->subject)
			->setBody($this->bodyHtml, 'text/html')
//			->setBody($bodyText, 'text/plain')
//			->addPart($bodyHtml, 'text/html')
			->setFrom($fromEmail)
			->setTo($toEmail)
		;
		return $message;
	}

	public function send($twigname, array $parameters, $toEmail, $fromEmail = null)
	{
		return $this->sendMessage($this->getMessage($twigname, $parameters, $toEmail, $fromEmail));
	}

	public function sendMessage($message)
	{
		$this->mailer->send($message, $fail);
		if ($fail) return $fail;
		return true;
	}

	public function addEmbeds(?array $embeds)
	{
		$this->embeds = $embeds;
	}

	public function getSubject()
	{
		return $this->subject;
	}

	public function getBodyHtml()
	{
		return $this->bodyHtml;
	}
}