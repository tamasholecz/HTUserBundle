<?php

namespace HT\UserBundle\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('email', EmailType::class)
			->add('username')
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
			->add('name')
			->add('phone')
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
