<?php

namespace MediaMonks\FirewallFilterBundle\Security\LoginFlow;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Interface LogoutAwareInterface
 * Implement this interface if you want your handler to be notified about logout to firewall.
 *
 * @package MediaMonks\FirewallFilterBundle\Security\LoginFlow
 * @author pawel@mediamonks.com
 */
interface LogoutAwareInterface
{
    public function onLogout(Request $request, Response $response, TokenInterface $token);
}