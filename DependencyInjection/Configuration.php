<?php

namespace HT\UserBundle\DependencyInjection;

use HT\UserBundle\Form\ChangePasswordType;
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
				->scalarNode('user_class')
					->defaultValue('App\Entity\User')
					->cannotBeEmpty()
				->end()
				->scalarNode('registration_form')
					->defaultValue(RegistrationType::class)
				->end()
				->scalarNode('profile_form')
					->defaultValue(ProfileType::class)
				->end()
				->scalarNode('change_password_form')
					->defaultValue(ChangePasswordType::class)
				->end()
			->end()
		;

		return $treeBuilder;
	}
}