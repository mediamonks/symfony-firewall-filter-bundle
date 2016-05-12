<?php

namespace MediaMonks\SecurityBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class GuardianProvider
 * @package MediaMonks\SecurityBundle\Security\Authentication\Provider
 * @author pawel@mediamonks.com
 */
class GuardianProvider implements AuthenticationProviderInterface
{
    public function supports(TokenInterface $token)
    {

    }

    public function authenticate(TokenInterface $token)
    {

    }
}