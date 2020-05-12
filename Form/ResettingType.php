<?php

namespace App\UserBundle\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResettingType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('plainPassword', RepeatedType::class, [
				'type' => PasswordType::class,
				'options' => [
					'attr' => [
						'autocomplete' => 'new-password',
					],
				],
				'first_options' => ['label' => 'Password'],
				'second_options' => ['label' => 'Password confirmation'],
				'invalid_message' => 'User password mismatch',
			])
		;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => User::class,
			'translation_domain' => 'user',
		]);
	}
}
