<?php

namespace HT\UserBundle\Controller;

use HT\UserBundle\Entity\HTUserInterface;
use HT\UserBundle\Event\FormEvent;
use HT\UserBundle\Event\UserEvent;
use HT\UserBundle\HTUserEvents;
use HT\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ProfileController extends AbstractController
{
    private $userManager;
    private $dispatcher;
    private $profileForm;

    public function __construct(UserManagerInterface $userManager, EventDispatcherInterface $dispatcher, string $profileForm)
    {
        $this->userManager = $userManager;
        $this->dispatcher = $dispatcher;
        $this->profileForm = $profileForm;
    }

    public function show(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

        return $this->render('@HTUser/profile_show.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    public function edit(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        /** @var HTUserInterface $user */
        $user = $this->getUser();

        $event = new UserEvent($user, $request);
        $this->dispatcher->dispatch($event, HTUserEvents::PROFILE_EDIT_INITIALIZE);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $this->createForm($this->profileForm, $user, ['data_class' => $this->userManager->getUserClass()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = new FormEvent($form, $request);
            $this->dispatcher->dispatch($event, HTUserEvents::PROFILE_EDIT_SUCCESS);

            $this->userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('user_profile_show');
                $response = new RedirectResponse($url);
            }

            $this->dispatcher->dispatch(new UserEvent($user, $request, $response), HTUserEvents::PROFILE_EDIT_COMPLETED);

            return $response;
        }

        return $this->render('@HTUser/profile_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
