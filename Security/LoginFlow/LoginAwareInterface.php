<?php

namespace MediaMonks\FirewallFilterBundle\Security\LoginFlow;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Interface LoginAwareInterface
 * Implement this interface if you want your handler to be notified about login to firewall.
 *
 * @package MediaMonks\FirewallFilterBundle\Security\LoginFlow
 * @author pawel@mediamonks.com
 */
interface LoginAwareInterface
{
    public function onLogin(InteractiveLoginEvent $event);
}