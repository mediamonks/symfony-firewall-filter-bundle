<?php

namespace MediaMonks\FirewallFilterBundle\Tests\Functional;

use MediaMonks\FirewallFilterBundle\DependencyInjection\Security\FirewallFilterFactory;
use MediaMonks\FirewallFilterBundle\Security\Firewall\FirewallFilterListener;
use MediaMonks\FirewallFilterBundle\Security\Listener\FirewallFilterLoginListener;
use MediaMonks\FirewallFilterBundle\Security\LoginFlow\CheckAwareInterface;
use MediaMonks\FirewallFilterBundle\Security\LoginFlow\LoginAwareInterface;
use MediaMonks\FirewallFilterBundle\Security\LoginFlow\LogoutAwareInterface;
use Mockery as m;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Class HandlersInvokedTest in package MediaMonks\FirewallFilterBundle\Tests\DependencyInjection\Functional
 *
 * @author pawel@mediamonks.com
 */
class HandlersInvokedTest extends AbstractFunctionalTestCase
{
    protected $testCase = 'Handlers';

    protected $firewall = 'functional_test';

    /**
     * @var \Symfony\Bundle\FrameworkBundle\Client
     */
    protected $client;

    public function setUp()
    {
        $this->client = static::createClient([
            'test_case' => $this->testCase,
            'root_config' => __DIR__ . '/app/Handlers/config.yml'
        ]);
    }

    public function testOnLogin()
    {
        $loginAware = m::mock(LoginAwareInterface::class)
            ->shouldReceive('onLogin')
            ->with(m::type(InteractiveLoginEvent::class))
            ->once()
            ->getMock();

        /** @var FirewallFilterLoginListener $testService */
        $testService = $this->client->getContainer()->get(FirewallFilterFactory::AUTH_CHECK_LISTENER);
        $testService->addHandler($this->firewall, $loginAware);

        $this->client->request('POST', '/test/login', [
            '_username' => 'user1',
            '_password' => 'user1'
        ]);
    }

    public function testOnLoginFail()
    {
        $loginAware = m::mock(LoginAwareInterface::class)
            ->shouldReceive('onLogin')
            ->with(m::type(InteractiveLoginEvent::class))
            ->never()
            ->getMock();

        /** @var FirewallFilterLoginListener $testService */
        $testService = $this->client->getContainer()->get(FirewallFilterFactory::AUTH_CHECK_LISTENER);
        $testService->addHandler($this->firewall, $loginAware);

        $this->client->request('POST', '/test/login', [
            '_username' => 'user',
            '_password' => 'not_exist'
        ]);
    }

    public function testOnCheck()
    {
        $checkAware = m::mock(CheckAwareInterface::class)
            ->shouldReceive('onCheck')
            ->with(m::type(GetResponseEvent::class))
            ->once()
            ->getMock();

        /** @var FirewallFilterListener $testService */
        $testService = $this->client->getContainer()->get(FirewallFilterFactory::getFirewallListenerName($this->firewall));
        $testService->addHandler($checkAware);

        $this->client->request('GET', '/test');
    }

    public function testOnLogoutNoUser()
    {
        /** @var FirewallFilterListener $testService */
        $testService = $this->client->getContainer()->get(FirewallFilterFactory::getLogoutHandlerName($this->firewall));
        $testService->addHandler(
            m::mock(LogoutAwareInterface::class)
                ->shouldReceive('onLogout')
                ->with(m::type(Request::class), m::type(Response::class), m::type(TokenInterface::class))
                ->never()
                ->getMock()
        );

        $this->client->request('GET', '/test/logout');
    }

    public function testOnLogoutUser()
    {
        /** @var FirewallFilterListener $testService */
        $testService = $this->client->getContainer()->get(FirewallFilterFactory::getLogoutHandlerName('functional_test'));
        $testService->addHandler(
            m::mock(LogoutAwareInterface::class)
                ->shouldReceive('onLogout')
                ->with(m::type(Request::class), m::type(Response::class), m::type(TokenInterface::class))
                ->once()
                ->getMock()
        );

        $token = new UsernamePasswordToken('user1', null, $this->firewall, array('ROLE_ADMIN'));
        $session = $this->client->getContainer()->get('session');
        $session->set('_security_' . $this->firewall, serialize($token));
        $session->save();

        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);

        $this->client->request('GET', '/test/logout');
    }
}