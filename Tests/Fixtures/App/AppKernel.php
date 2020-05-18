<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
	public function __construct()
	{
		parent::__construct('test', true);
	}

	public function registerBundles()
	{
		return [
			new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
			new Symfony\Bundle\SecurityBundle\SecurityBundle(),
			new Symfony\Bundle\TwigBundle\TwigBundle(),
			new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
			new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
			new \HT\UserBundle\HTUserBundle,
		];
	}

	public function registerContainerConfiguration(LoaderInterface $loader)
	{
		$loader->load(__DIR__.'\config\config_'.$this->getEnvironment().'.yml');

		$loader->load(function (ContainerBuilder $container) {
		});
	}

	public function getProjectDir(): string
	{
		return __DIR__;
	}

	public function getCacheDir(): string
	{
		return __DIR__.'/../../build/cache/'.$this->getEnvironment();
	}

	public function getLogDir(): string
	{
		return __DIR__.'/../../build/kernel_logs/'.$this->getEnvironment();
	}
}
