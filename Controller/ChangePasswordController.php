<?php

namespace HT\UserBundle\Controller;

use HT\UserBundle\Doctrine\UserManager;
use HT\UserBundle\Entity\User;
use HT\UserBundle\Event\FormEvent;
use HT\UserBundle\Event\UserEvent;
use HT\UserBundle\HTUserEvents;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ChangePasswordController extends AbstractController
{
	public function changePassword(UserManager $userManager, EventDispatcherInterface $dispatcher, Request $request): Response
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		/** @var User $user */
		$user = $this->getUser();

		$event = new UserEvent($user, $request);
		$dispatcher->dispatch($event, HTUserEvents::CHANGE_PASSWORD_INITIALIZE);
		if (null !== $event->getResponse()) {
			return $event->getResponse();
		}

		$form = $this->createForm(\HT\UserBundle\Form\ChangePasswordType::class, $user);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$event = new FormEvent($form, $request);
			$dispatcher->dispatch($event, HTUserEvents::CHANGE_PASSWORD_SUCCESS);

			$userManager->updateUser($user);

			if (null === $response = $event->getResponse()) {
				$url = $this->generateUrl('user_profile_show');
				$response = new RedirectResponse($url);
			}

			$dispatcher->dispatch(new UserEvent($user, $request, $response), HTUserEvents::CHANGE_PASSWORD_COMPLETED);

			return $response;
		}

		return $this->render('@HTUser/change_password.html.twig', array(
			'form' => $form->createView(),
		));
	}
}
