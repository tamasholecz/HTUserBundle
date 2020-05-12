<?php

namespace App\UserBundle\Controller;

use App\UserBundle\Doctrine\UserManager;
use App\UserBundle\Entity\User;
use App\UserBundle\Event\FormEvent;
use App\UserBundle\Event\UserEvent;
use App\UserBundle\HTUserEvents;
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

		$form = $this->createForm(\App\UserBundle\Form\ChangePasswordType::class, $user);
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

		return $this->render('@User/change_password.html.twig', array(
			'form' => $form->createView(),
		));
	}
}
