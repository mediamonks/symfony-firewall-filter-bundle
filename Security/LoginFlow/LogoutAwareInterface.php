<?php

namespace MediaMonks\SecurityBundle\Security\LoginFlow;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Interface LogoutAwareInterface
 * @package MediaMonks\SecurityBundle\Security\LoginFlow
 * @author pawel@mediamonks.com
 */
interface LogoutAwareInterface
{
    public function onLogout(Request $request, Response $response, TokenInterface $token);
}