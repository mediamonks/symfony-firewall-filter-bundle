<?php

namespace MediaMonks\FirewallFilterBundle\Security\Http\Logout;

use MediaMonks\FirewallFilterBundle\Security\LoginFlow\LogoutAwareInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;

/**
 * Class LogoutEventDispatchHandler
 * Listens to logout event for certain firewall.
 *
 * @package MediaMonks\FirewallFilterBundle\Security\Http\Logout
 * @author pawel@mediamonks.com
 */
class LogoutFirewallHandler implements LogoutHandlerInterface
{
    /**
     * @var LogoutAwareInterface[]
     */
    protected $handlers = [];

    /**
     * @param LogoutAwareInterface $handler
     */
    public function addHandler(LogoutAwareInterface $handler)
    {
        $this->handlers[] = $handler;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param TokenInterface $token
     */
    public function logout(Request $request, Response $response, TokenInterface $token)
    {
        foreach ($this->handlers as $handler){
            $handler->onLogout($request, $response, $token);
        }
    }
}