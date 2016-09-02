<?php

namespace MediaMonks\FirewallFilterBundle\Security\LoginFlow;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Interface CheckAwareInterface
 * Implement this interface if you want your handler to be notified about request against certain firewall.
 *
 * @package MediaMonks\FirewallFilterBundle\Security\LoginFlow
 * @author pawel@mediamonks.com
 */
interface CheckAwareInterface
{
    public function onCheck(GetResponseEvent $event);
}