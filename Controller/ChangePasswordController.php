<?php

namespace HT\UserBundle\Controller;

use HT\UserBundle\Entity\User;
use HT\UserBundle\Event\FormEvent;
use HT\UserBundle\Event\UserEvent;
use HT\UserBundle\HTUserEvents;
use HT\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ChangePasswordController extends AbstractController
{
	private $userManager;
	private $dispatcher;

	public function __construct(UserManagerInterface $userManager, EventDispatcherInterface $dispatcher)
	{
		$this->userManager = $userManager;
		$this->dispatcher = $dispatcher;
	}

	public function changePassword(Request $request): Response
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		/** @var User $user */
		$user = $this->getUser();

		$event = new UserEvent($user, $request);
		$this->dispatcher->dispatch($event, HTUserEvents::CHANGE_PASSWORD_INITIALIZE);
		if (null !== $event->getResponse()) {
			return $event->getResponse();
		}

		$form = $this->createForm(\HT\UserBundle\Form\ChangePasswordType::class, $user);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$event = new FormEvent($form, $request);
			$this->dispatcher->dispatch($event, HTUserEvents::CHANGE_PASSWORD_SUCCESS);

			$this->userManager->updateUser($user);

			if (null === $response = $event->getResponse()) {
				$url = $this->generateUrl('user_profile_show');
				$response = new RedirectResponse($url);
			}

			$this->dispatcher->dispatch(new UserEvent($user, $request, $response), HTUserEvents::CHANGE_PASSWORD_COMPLETED);

			return $response;
		}

		return $this->render('@HTUser/change_password.html.twig', array(
			'form' => $form->createView(),
		));
	}
}
