<?php

namespace HT\UserBundle\Tests\Controller;

use HT\UserBundle\Entity\HTUserInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\HttpFoundation\Response;

class RegistrationControllerTest extends WebTestCase
{
	/** @var \AppKernel */
	private $appKernel;

	public function testRegister()
	{
		require __DIR__.'/../Fixtures/App/AppKernel.php';
		$this->appKernel = new \AppKernel();

		$application = new Application($this->appKernel);
		$application->setAutoExit(false);

		$input = new ArrayInput(['command' => 'doctrine:schema:drop', '-f' => true]);
		$application->run($input, new ConsoleOutput());
		$input = new ArrayInput(['command' => 'doctrine:schema:update', '-f' => true]);//, '--dump-sql' => true
		$application->run($input, new ConsoleOutput());

		unset($input, $application);

		$client = new KernelBrowser($this->appKernel);
		$crawler = $client->request('GET', '/registration');
		$crawler = $client->followRedirect();

		$this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
		$email = 'test@test.hu';
		$form = $crawler->filter('form button[type=submit]')->form([
			'registration[email]' => $email,
			'registration[username]' => 'test',
			'registration[plainPassword][first]' => '1234',
			'registration[plainPassword][second]' => '1234',
			'registration[name]' => 'Teszt Elek',
			'registration[phone]' => '+36301234567',
		]);

		$crawler = $client->submit($form);
		$crawler = $client->followRedirect();

		$this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
		$this->assertSame('Registration check email', $crawler->filter('body')->text());

		/** @var HTUserInterface $user */
		$user = $this->getUserManager()->findUserByEmail($email);
		$this->assertSame('test', $user->getUserName());
		$this->assertFalse($user->getEnabled());

		// Try show profile (disabled)

		$client->request('GET', '/profile/');
		$this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());

		// Registration confirm

		$crawler = $client->request('GET', '/registration/confirm/'.$user->getConfirmationToken());
		$crawler = $client->followRedirect();

		$this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
		$this->assertSame('Registration confirmed', $crawler->filter('body')->text());

		$user = $this->getUserManager()->findUserByEmail($email);

		$this->asserttrue($user->getEnabled());
		$this->assertNull($user->getConfirmationToken());
		$this->assertNotNull($user->getLastLogin());

		// Show profile

		$crawler = $client->request('GET', '/profile/');

		$this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
		$this->assertContains('Email: '.$email, $crawler->filter('body')->text());

		// Logout

		$crawler = $client->request('GET', '/logout');
		$this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode());
		$this->assertSame('http://localhost/', $client->getResponse()->headers->get('location'));

		// Resetting password

		$crawler = $client->request('GET', '/resetting/request');
		$this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

		$form = $crawler->filter('form button[type=submit]')->form([
			'username' => $email,
		]);

		$crawler = $client->submit($form);
		$crawler = $client->followRedirect();

		$this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
		$this->assertSame('Resetting check email', $crawler->filter('body')->text());

		$user = $this->getUserManager()->findUserByEmail($email);

		$crawler = $client->request('GET', '/resetting/reset/'.$user->getConfirmationToken());
		$this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

		$form = $crawler->filter('form button[type=submit]')->form([
			'resetting[plainPassword][first]' => '12345',
			'resetting[plainPassword][second]' => '12345',
		]);

		$crawler = $client->submit($form);
		$crawler = $client->followRedirect();

		$this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
		$this->assertContains('Email: '.$email, $crawler->filter('body')->text());

		$user = $this->getUserManager()->findUserByEmail($email);
		$this->assertNull($user->getConfirmationToken());

		// Logout

		$client->request('GET', '/logout');

		// Login with old password

		$crawler = $client->request('GET', '/login');
		$this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

		$form = $crawler->filter('form button[type=submit]')->form([
			'_username' => $email,
			'_password' => '1234',
		]);

		$crawler = $client->submit($form);
		$crawler = $client->followRedirect();

		$this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
		$this->assertContains('Invalid credentials.', $crawler->filter('body')->text());

		// Login with new password

		$form = $crawler->filter('form button[type=submit]')->form([
			'_username' => $email,
			'_password' => '12345',
		]);

		$crawler = $client->submit($form);
		$this->assertSame('/', $client->getResponse()->headers->get('location'));
	}

	public function getUserManager()
	{
		return $this->appKernel->getContainer()->get('htuser.user_manager');
	}
}