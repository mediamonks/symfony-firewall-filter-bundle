<?php

namespace MediaMonks\FirewallFilterBundle\Tests\DependencyInjection\CompilerPass;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use MediaMonks\FirewallFilterBundle\DependencyInjection\CompilerPass\FilterFlowPass;
use MediaMonks\FirewallFilterBundle\DependencyInjection\Security\FirewallFilterFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use \Mockery as m;

/**
 * Class FilterFlowCompilerTest
 * @package MediaMonks\FirewallFilterBundle\Tests\DependencyInjection\CompilerPass
 * @author pawel@mediamonks.com
 */
class FilterFlowCompilerTest extends AbstractCompilerPassTestCase
{
	protected function registerCompilerPass(ContainerBuilder $container)
	{
		$container->addCompilerPass(new FilterFlowPass());
	}

	public function testLoginHandlerAdded()
	{
		$id = 'test';
		$handler = 'test_handler';
		$this->compileHandler($id, $handler, "MediaMonks\FirewallFilterBundle\Security\LoginFlow\LoginAwareInterface");

		$this->assertContainerBuilderHasServiceDefinitionWithMethodCall(FirewallFilterFactory::AUTH_CHECK_LISTENER, 'addHandler', [
			$id, $this->container->findDefinition($handler)
		]);
	}

	public function testCheckHandlerAdded()
	{
		$id = 'test';
		$handler = 'test_handler';
		$this->compileHandler($id, $handler, "MediaMonks\FirewallFilterBundle\Security\LoginFlow\CheckAwareInterface");

		$this->assertContainerBuilderHasServiceDefinitionWithMethodCall(FirewallFilterFactory::getFirewallListenerName($id), 'addHandler', [
			$this->container->findDefinition($handler)
		]);
	}

	public function testLogoutHandlerAdded()
	{
		$id = 'test';
		$handler = 'test_handler';
		$this->compileHandler($id, $handler, "MediaMonks\FirewallFilterBundle\Security\LoginFlow\LogoutAwareInterface");

		$this->assertContainerBuilderHasServiceDefinitionWithMethodCall(FirewallFilterFactory::getLogoutHandlerName($id), 'addHandler', [
			$this->container->findDefinition($handler)
		]);
	}

	protected function compileHandler($id, $handler, $handlerClass)
	{
		$config = [
			'handlers' => [$handler]
		];

		$loginHandler = m::mock($handlerClass);
		$this->registerService($handler, $loginHandler->mockery_getName());
		$this->performFactory($id, $config);

		$this->compile();
	}

	protected function performFactory($id, $config)
	{
		$userProviderId = 'test';
		$entryPoint = 'test';

		$this->registerService(FirewallFilterFactory::AUTH_CHECK_LISTENER, null);
		$this->registerService(FirewallFilterFactory::AUTH_PROVIDER, null);
		$this->registerService(FirewallFilterFactory::AUTH_FIREWALL_LISTENER, null);
		$this->registerService(FirewallFilterFactory::SYMFONY_LOGOUT_LISTENER . '.' . $id, null);

		$factory = new FirewallFilterFactory();
		$factory->create($this->container, $id, $config, $userProviderId, $entryPoint);
	}
}
