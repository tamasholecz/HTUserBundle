<?php

namespace App\UserBundle\Event;

use App\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

class UserEvent extends Event
{
	protected $request;
	protected $user;
	private $response;

	public function __construct(User $user, Request $request = null, Response $response = null)
	{
		$this->user = $user;
		$this->request = $request;
		$this->response = $response;
	}

	public function getUser(): User
	{
		return $this->user;
	}

	public function getRequest(): ?Request
	{
		return $this->request;
	}

	public function setResponse(Response $response)
	{
		$this->response = $response;
	}

	public function getResponse(): ?Response
	{
		return $this->response;
	}
}
