<?php

namespace App\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
	public function login(AuthenticationUtils $authenticationUtils): Response
	{
//		if ($this->getUser()) { return $this->redirectToRoute('sonata_admin_dashboard'); }
		$error = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render('security/login.html.twig', [
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
