<?php

namespace HT\UserBundle\Tests\Routing;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouteCollectionBuilder;

class RoutingTest extends TestCase
{
	public function getRouteCollection(): RouteCollection
	{
		$locator = new FileLocator();
		$loader = new YamlFileLoader($locator);
		$routeBuilder = new RouteCollectionBuilder($loader);
		$routeBuilder->import(__DIR__.'/../../Resources/config/routing/all.yaml');

		return $routeBuilder->build();
	}

	/**
	 * @dataProvider loadRoutingProvider
	 */
	public function testLoadRouting(string $routeName, string $path, array $methods)
	{
		$collection = $this->getRouteCollection();

		$route = $collection->get($routeName);
		$this->assertNotNull($route, sprintf('The route "%s" should exists', $routeName));
		$this->assertSame($path, $route->getPath());
		$this->assertSame($methods, $route->getMethods());
	}

	/**
	 * @return array
	 */
	public function loadRoutingProvider()
	{
		return [
			['login', '/login', ['GET', 'POST']],
			['logout', '/logout', ['GET']],

			['user_registration_register', '/registration/', ['GET', 'POST']],
			['user_registration_check_email', '/registration/check-email', ['GET']],
			['user_registration_confirm', '/registration/confirm/{token}', ['GET']],
			['user_registration_confirmed', '/registration/confirmed', ['GET']],

			['user_profile_show', '/profile/', ['GET']],
			['user_profile_edit', '/profile/edit', ['GET', 'POST']],

			['user_change_password', '/profile/change-password', ['GET', 'POST']],

			['user_resetting_request', '/resetting/request', ['GET']],
			['user_resetting_send_email', '/resetting/send-email', ['POST']],
			['user_resetting_check_email', '/resetting/check-email', ['GET']],
			['user_resetting_reset', '/resetting/reset/{token}', ['GET', 'POST']],
		];
	}
}