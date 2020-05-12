<?php

namespace App\UserBundle\Util;

interface TokenGeneratorInterface
{
	public function generateToken(): string;
}
