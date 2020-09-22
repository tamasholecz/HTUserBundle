<?php

namespace HT\UserBundle\Controller;

use HT\UserBundle\Event\FormEvent;
use HT\UserBundle\Event\UserEvent;
use HT\UserBundle\HTUserEvents;
use HT\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class RegistrationController extends AbstractController
{
	private $userManager;
	private $dispatcher;
	private $registrationForm;

	public function __construct(UserManagerInterface $userManager, EventDispatcherInterface $dispatcher, string $registrationForm)
	{
		$this->userManager = $userManager;
		$this->dispatcher = $dispatcher;
		$this->registrationForm = $registrationForm;
	}

	public function register(Request $request): Response
	{
		$user = $this->userManager->createUser();

		$event = new UserEvent($user, $request);
		$this->dispatcher->dispatch($event, HTUserEvents::REGISTRATION_INITIALIZE);
		if (null !== $event->getResponse()) {
			return $event->getResponse();
		}

		$form = $this->createForm($this->registrationForm, $user, ['data_class' => $this->userManager->getUserClass()]);
		$form->handleRequest($request);
		if ($form->isSubmitted()) {
			if ($form->isValid()) {
				$event = new FormEvent($form, $request);
				$this->dispatcher->dispatch($event, HTUserEvents::REGISTRATION_SUCCESS);

				$this->userManager->updateUser($user);

				if (null === $response = $event->getResponse()) {
					$response = new RedirectResponse($this->generateUrl('user_registration_confirmed'));
				}

				$this->dispatcher->dispatch(new UserEvent($user, $request, $response), HTUserEvents::REGISTRATION_COMPLETED);
				return $response;
			}

			$event = new FormEvent($form, $request);
			$this->dispatcher->dispatch($event, HTUserEvents::REGISTRATION_FAILURE);

			if (null !== $response = $event->getResponse()) {
				return $response;
			}
		}

		return $this->render('@HTUser/registration_register.html.twig', [
			'form' => $form->createView(),
		]);
	}

	public function checkEmail(Request $request): Response
	{
		$email = $request->getSession()->get('user_send_confirmation_email/email');

		if (empty($email)) {
			return new RedirectResponse($this->generateUrl('user_registration_register'));
		}

		$request->getSession()->remove('user_send_confirmation_email/email');
		$user = $this->userManager->findUserBy(['email' => $email]);

		if (null === $user) {
			return new RedirectResponse($this->container->get('router')->generate('login'));
		}

		return $this->render('@HTUser/registration_check_email.html.twig', [
			'user' => $user,
		]);
	}

	public function confirm(Request $request, string $token): Response
	{
		$user = $this->userManager->findUserBy(['confirmationToken' => $token]);

		if (null === $user) {
			throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
		}

		$user->setConfirmationToken(null);
		$user->setEnabled(true);

		$event = new UserEvent($user, $request);
		$this->dispatcher->dispatch($event, HTUserEvents::REGISTRATION_CONFIRM);

		$this->userManager->updateUser($user);

		if (null === $response = $event->getResponse()) {
			$response = new RedirectResponse($this->generateUrl('user_registration_confirmed'));
		}

		$this->dispatcher->dispatch(new UserEvent($user, $request, $response), HTUserEvents::REGISTRATION_CONFIRMED);

		return $response;
	}

	public function confirmed(Request $request): Response
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

		return $this->render(
			'@HTUser/registration_confirmed.html.twig', [
			'user' => $this->getUser(),
		]);
	}
}
