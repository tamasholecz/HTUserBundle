<?php

namespace App\UserBundle\Controller;

use App\UserBundle\Doctrine\UserManager;
use App\UserBundle\Event\FormEvent;
use App\UserBundle\Event\UserEvent;
use App\UserBundle\HTUserEvents;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class RegistrationController extends AbstractController
{
	public function register(UserManager $userManager, EventDispatcherInterface $dispatcher, Request $request): Response
	{
		$user = $userManager->createUser();

		$event = new UserEvent($user, $request);
		$dispatcher->dispatch($event, HTUserEvents::REGISTRATION_INITIALIZE);
		if (null !== $event->getResponse()) {
			return $event->getResponse();
		}

		$form = $this->createForm(\App\UserBundle\Form\RegistrationType::class, $user);
		$form->handleRequest($request);
		if ($form->isSubmitted()) {
			if ($form->isValid()) {
				$event = new FormEvent($form, $request);
				$dispatcher->dispatch($event, HTUserEvents::REGISTRATION_SUCCESS);

				$userManager->updateUser($user);

				if (null === $response = $event->getResponse()) {
					$response = new RedirectResponse($this->generateUrl('user_registration_confirmed'));
				}

				$dispatcher->dispatch(new UserEvent($user, $request, $response), HTUserEvents::REGISTRATION_COMPLETED);
				return $response;
			}

			$event = new FormEvent($form, $request);
			$dispatcher->dispatch($event, HTUserEvents::REGISTRATION_FAILURE);

			if (null !== $response = $event->getResponse()) {
				return $response;
			}
		}

		return $this->render('@User/registration_register.html.twig', [
			'form' => $form->createView(),
		]);
	}

	public function checkEmail(UserManager $userManager, Request $request): Response
	{
		$email = $request->getSession()->get('user_send_confirmation_email/email');

		if (empty($email)) {
			return new RedirectResponse($this->generateUrl('user_registration_register'));
		}

		$request->getSession()->remove('user_send_confirmation_email/email');
		$user = $userManager->findUserBy(['email' => $email]);

		if (null === $user) {
			return new RedirectResponse($this->container->get('router')->generate('login'));
		}

		return $this->render('@User/registration_check_email.html.twig', [
			'user' => $user,
		]);
	}

	public function confirm(UserManager $userManager, EventDispatcherInterface $dispatcher, Request $request, string $token): Response
	{
		$user = $userManager->findUserBy(['confirmationToken' => $token]);

		if (null === $user) {
			throw new NotFoundHttpException(sprintf('The user with confirmation token "%s" does not exist', $token));
		}

		$user->setConfirmationToken(null);
		$user->setEnabled(true);

		$event = new UserEvent($user, $request);
		$dispatcher->dispatch($event, HTUserEvents::REGISTRATION_CONFIRM);

		$userManager->updateUser($user);

		if (null === $response = $event->getResponse()) {
			$response = new RedirectResponse($this->generateUrl('user_registration_confirmed'));
		}

		$dispatcher->dispatch(new UserEvent($user, $request, $response), HTUserEvents::REGISTRATION_CONFIRMED);

		return $response;
	}

	public function confirmed(Request $request): Response
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

		return $this->render(
			'@User/registration_confirmed.html.twig', [
			'user' => $this->getUser(),
		]);
	}
}
