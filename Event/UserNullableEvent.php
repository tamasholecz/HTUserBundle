<?php

namespace HT\UserBundle\Event;

use HT\UserBundle\Entity\HTUserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\Event;

class UserNullableEvent extends Event
{
    protected $request;
    protected $user;
    private $response;

    public function __construct(?HTUserInterface $user, Request $request = null, Response $response = null)
    {
        $this->user = $user;
        $this->request = $request;
        $this->response = $response;
    }

    public function getUser(): ?HTUserInterface
    {
        return $this->user;
    }

    public function getRequest(): ?Request
    {
        return $this->request;
    }

    public function setResponse(Response $response): void
    {
        $this->response = $response;
    }

    public function getResponse(): ?Response
    {
        return $this->response;
    }
}
