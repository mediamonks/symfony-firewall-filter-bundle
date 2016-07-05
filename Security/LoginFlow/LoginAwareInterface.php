<?php

namespace MediaMonks\FirewallFilterBundle\Security\LoginFlow;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Interface LoginAwareInterface
 * @package MediaMonks\FirewallFilterBundle\Security\LoginFlow
 * @author pawel@mediamonks.com
 */
interface LoginAwareInterface
{
    public function onLogin(InteractiveLoginEvent $event);
}