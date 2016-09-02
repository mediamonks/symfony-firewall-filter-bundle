<?php

namespace MediaMonks\FirewallFilterBundle\Tests\Functional;

use MediaMonks\FirewallFilterBundle\DependencyInjection\Security\FirewallFilterFactory;
use MediaMonks\FirewallFilterBundle\Security\Firewall\FirewallFilterListener;
use MediaMonks\FirewallFilterBundle\Security\Listener\FirewallFilterLoginListener;
use MediaMonks\FirewallFilterBundle\Security\LoginFlow\CheckAwareInterface;
use MediaMonks\FirewallFilterBundle\Security\LoginFlow\LoginAwareInterface;
use MediaMonks\FirewallFilterBundle\Security\LoginFlow\LogoutAwareInterface;
use Mockery as m;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Class HandlersInvokedTest in package MediaMonks\FirewallFilterBundle\Tests\DependencyInjection\Functional
 *
 * @author pawel@mediamonks.com
 */
class HandlersInvokedTest extends AbstractFunctionalTestCase
{
    protected $testCase = 'Handlers';

    public function testOnLogin()
    {
        $kernel = static::createKernel([
            'test_case' => $this->testCase,
            'root_config' => __DIR__ . '/app/Handlers/config.yml'
        ]);
        $kernel->boot();

        $loginAware = m::mock(LoginAwareInterface::class)
            ->shouldReceive('onLogin')
            ->with(m::type(InteractiveLoginEvent::class))
            ->once()
            ->getMock();

        /** @var FirewallFilterLoginListener $testService */
        $testService = $kernel->getContainer()->get(FirewallFilterFactory::AUTH_CHECK_LISTENER);
        $testService->addHandler('functional_test', $loginAware);

        $client = $kernel->getContainer()->get('test.client');

        $response = $client->request('POST', '/test/login', [
            '_username' => 'user1',
            '_password' => 'user1'
        ]);
    }

    public function testOnLoginFail()
    {
        $kernel = static::createKernel([
            'test_case' => $this->testCase,
            'root_config' => __DIR__ . '/app/Handlers/config.yml'
        ]);
        $kernel->boot();

        $loginAware = m::mock(LoginAwareInterface::class)
            ->shouldReceive('onLogin')
            ->with(m::type(InteractiveLoginEvent::class))
            ->never()
            ->getMock();

        /** @var FirewallFilterLoginListener $testService */
        $testService = $kernel->getContainer()->get(FirewallFilterFactory::AUTH_CHECK_LISTENER);
        $testService->addHandler('functional_test', $loginAware);

        $client = $kernel->getContainer()->get('test.client');

        $response = $client->request('POST', '/test/login', [
            '_username' => 'user',
            '_password' => 'not_exist'
        ]);
    }

    public function testOnCheck()
    {
        $kernel = static::createKernel([
            'test_case' => $this->testCase,
            'root_config' => __DIR__ . '/app/Handlers/config.yml'
        ]);
        $kernel->boot();

        $checkAware = m::mock(CheckAwareInterface::class)
            ->shouldReceive('onCheck')
            ->with(m::type(GetResponseEvent::class))
            ->once()
            ->getMock();

        /** @var FirewallFilterListener $testService */
        $testService = $kernel->getContainer()->get(FirewallFilterFactory::getFirewallListenerName('functional_test'));
        $testService->addHandler($checkAware);

        $client = $kernel->getContainer()->get('test.client');

        $response = $client->request('GET', '/test');
    }

    public function atestOnLogout()
    {
        $kernel = static::createKernel([
            'test_case' => $this->testCase,
            'root_config' => __DIR__ . '/app/Handlers/config.yml'
        ]);

        $kernel->boot();

        /** @var FirewallFilterListener $testService */
        $testService = $kernel->getContainer()->get(FirewallFilterFactory::getLogoutHandlerName('functional_test'));
        $testService->addHandler(
            m::mock(LogoutAwareInterface::class)
                ->shouldReceive('onLogout')
                ->with(m::type(Request::class), m::type(Response::class), m::type(TokenInterface::class))
                ->once()
                ->getMock()
        );

        $client = $kernel->getContainer()->get('test.client');

        $response = $client->request('GET', '/test');
    }
}