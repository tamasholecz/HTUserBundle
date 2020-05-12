<?php

namespace HT\UserBundle\Util;

interface TokenGeneratorInterface
{
	public function generateToken(): string;
}
