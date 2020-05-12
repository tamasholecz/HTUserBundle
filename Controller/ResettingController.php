<?php

namespace HT\UserBundle\Controller;

use HT\UserBundle\Doctrine\UserManager;
use HT\UserBundle\Event\FormEvent;
use HT\UserBundle\Event\UserEvent;
use HT\UserBundle\Mailer\Mailer;
use HT\UserBundle\HTUserEvents;
use HT\UserBundle\Util\TokenGeneratorInterface;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ResettingController extends AbstractController
{
	private $retryTtl = 7200;

	public function request()
	{
		return $this->render('@User/resetting_request.html.twig');
	}

	public function sendEmail(UserManager $userManager, EventDispatcherInterface $dispatcher, TokenGeneratorInterface $tokenGenerator, Mailer $mailer, Request $request)
	{
		$username = $request->request->get('username');

		$user = $userManager->findUserByUsernameOrEmail($username);
		// TODO: if user null

		$event = new UserEvent($user, $request);
		$dispatcher->dispatch($event, HTUserEvents::RESETTING_SEND_EMAIL_INITIALIZE);
		if (null !== $event->getResponse()) {
			return $event->getResponse();
		}

		if (null !== $user) {// TODO: && !$user->isPasswordRequestNonExpired($this->retryTtl)
			$event = new UserEvent($user, $request);
			$dispatcher->dispatch($event, HTUserEvents::RESETTING_RESET_REQUEST);
			if (null !== $event->getResponse()) {
				return $event->getResponse();
			}

			if (null === $user->getConfirmationToken()) {
				$user->setConfirmationToken($tokenGenerator->generateToken());
			}

			$event = new UserEvent($user, $request);
			$dispatcher->dispatch($event, HTUserEvents::RESETTING_SEND_EMAIL_CONFIRM);
			if (null !== $event->getResponse()) {
				return $event->getResponse();
			}

			$mailer->sendResettingEmailMessage($user);
			$user->setPasswordRequestedAt(new DateTime());
			$userManager->updateUser($user);

			$event = new UserEvent($user, $request);
			$dispatcher->dispatch($event, HTUserEvents::RESETTING_SEND_EMAIL_COMPLETED);
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

		return $this->render('@User/resetting_check_email.html.twig', [
			'tokenLifetime' => ceil($this->retryTtl / 3600),
		]);
	}

	public function reset(UserManager $userManager, EventDispatcherInterface $dispatcher, Request $request, string $token)
	{
		$user = $userManager->findUserByConfirmationToken($token);

		if (null === $user) {
			return new RedirectResponse($this->container->get('router')->generate('login'));
		}

		$event = new UserEvent($user, $request);
		$dispatcher->dispatch($event, HTUserEvents::RESETTING_RESET_INITIALIZE);

		if (null !== $event->getResponse()) {
			return $event->getResponse();
		}

		$form = $this->createForm(\HT\UserBundle\Form\ResettingType::class, $user);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$event = new FormEvent($form, $request);
			$dispatcher->dispatch($event, HTUserEvents::RESETTING_RESET_SUCCESS);

			$userManager->updateUser($user);

			if (null === $response = $event->getResponse()) {
				$url = $this->generateUrl('user_profile_show');
				$response = new RedirectResponse($url);
			}

			$dispatcher->dispatch(new UserEvent($user, $request, $response), HTUserEvents::RESETTING_RESET_COMPLETED);

			return $response;
		}

		return $this->render('@User/resetting_reset.html.twig', array(
			'token' => $token,
			'form' => $form->createView(),
		));
	}
}
