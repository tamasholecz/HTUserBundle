<?php

namespace HT\UserBundle\Controller;

use HT\UserBundle\Event\FormEvent;
use HT\UserBundle\Event\UserEvent;
use HT\UserBundle\Event\UserNullableEvent;
use HT\UserBundle\Mailer\UserMailer;
use HT\UserBundle\HTUserEvents;
use HT\UserBundle\Model\UserManagerInterface;
use HT\UserBundle\Util\TokenGeneratorInterface;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ResettingController extends AbstractController
{
	private $userManager;
	private $tokenGenerator;
	private $mailer;
	private $dispatcher;

	private $retryTtl = 7200;

	public function __construct(UserManagerInterface $userManager, TokenGeneratorInterface $tokenGenerator, UserMailer $mailer, EventDispatcherInterface $dispatcher)
	{
		$this->userManager = $userManager;
		$this->tokenGenerator = $tokenGenerator;
		$this->mailer = $mailer;
		$this->dispatcher = $dispatcher;
	}

	public function request()
	{
		return $this->render('@HTUser/resetting_request.html.twig');
	}

	public function sendEmail(Request $request)
	{
		$username = trim($request->request->get('username'));

		$user = $this->userManager->findUserByUsernameOrEmail($username);

		$event = new UserNullableEvent($user, $request);
		$this->dispatcher->dispatch($event, HTUserEvents::RESETTING_SEND_EMAIL_INITIALIZE);
		if (null !== $event->getResponse()) {
			return $event->getResponse();
		}

		if (null !== $user) {// TODO: && !$user->isPasswordRequestNonExpired($this->retryTtl)
			$event = new UserEvent($user, $request);
			$this->dispatcher->dispatch($event, HTUserEvents::RESETTING_RESET_REQUEST);
			if (null !== $event->getResponse()) {
				return $event->getResponse();
			}

			if (null === $user->getConfirmationToken()) {
				$user->setConfirmationToken($this->tokenGenerator->generateToken());
			}

			$event = new UserEvent($user, $request);
			$this->dispatcher->dispatch($event, HTUserEvents::RESETTING_SEND_EMAIL_CONFIRM);
			if (null !== $event->getResponse()) {
				return $event->getResponse();
			}

			$this->mailer->sendResettingEmailMessage($user);
			$user->setPasswordRequestedAt(new DateTime());
			$this->userManager->updateUser($user);

			$event = new UserEvent($user, $request);
			$this->dispatcher->dispatch($event, HTUserEvents::RESETTING_SEND_EMAIL_COMPLETED);
			if (null !== $event->getResponse()) {
				return $event->getResponse();
			}
		}

		return new RedirectResponse($this->generateUrl('user_resetting_check_email', ['username' => $username]));
	}

	public function checkEmail(Request $request)
	{
		$username = $request->query->get('username');

		if (empty($username)) {
			return new RedirectResponse($this->generateUrl('user_resetting_request'));
		}

		return $this->render('@HTUser/resetting_check_email.html.twig', [
			'tokenLifetime' => ceil($this->retryTtl / 3600),
		]);
	}

	public function reset(Request $request, string $token)
	{
		$user = $this->userManager->findUserByConfirmationToken($token);

		if (null === $user) {
			return new RedirectResponse($this->container->get('router')->generate('login'));
		}

		$event = new UserEvent($user, $request);
		$this->dispatcher->dispatch($event, HTUserEvents::RESETTING_RESET_INITIALIZE);

		if (null !== $event->getResponse()) {
			return $event->getResponse();
		}

		$form = $this->createForm(\HT\UserBundle\Form\ResettingType::class, $user, ['data_class' => $this->userManager->getUserClass()]);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$event = new FormEvent($form, $request);
			$this->dispatcher->dispatch($event, HTUserEvents::RESETTING_RESET_SUCCESS);

			$user->setConfirmationToken(null);
			$this->userManager->updateUser($user);

			if (null === $response = $event->getResponse()) {
				$url = $this->generateUrl('user_profile_show');
				$response = new RedirectResponse($url);
			}

			$this->dispatcher->dispatch(new UserEvent($user, $request, $response), HTUserEvents::RESETTING_RESET_COMPLETED);

			return $response;
		}

		return $this->render('@HTUser/resetting_reset.html.twig', [
			'token' => $token,
			'form' => $form->createView(),
		]);
	}
}
