<?php

namespace HT\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
	private $authenticationUtils;

	public function __construct(AuthenticationUtils $authenticationUtils)
	{
		$this->authenticationUtils = $authenticationUtils;
	}

	public function login(): Response
	{
//		if ($this->getUser()) { return $this->redirectToRoute('sonata_admin_dashboard'); }
		$error = $this->authenticationUtils->getLastAuthenticationError();
		$lastUsername = $this->authenticationUtils->getLastUsername();

		return $this->render('@HTUser/login.html.twig', [
			'error' => $error,
			'last_username' => $lastUsername,
			'csrf_token_intention' => 'authenticate',
		]);
	}

	public function logout()
	{
		throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
	}
}
