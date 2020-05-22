<?php

namespace HT\UserBundle\DependencyInjection;

use HT\UserBundle\Form\ProfileType;
use HT\UserBundle\Form\RegistrationType;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
	public function getConfigTreeBuilder()
	{
		$treeBuilder = new TreeBuilder('ht_user');

		$treeBuilder->getRootNode()
			->children()
				->scalarNode('registration_form')->defaultValue(RegistrationType::class)->end()
				->scalarNode('profile_form')->defaultValue(ProfileType::class)->end()
			->end()
		;

		return $treeBuilder;
	}
}