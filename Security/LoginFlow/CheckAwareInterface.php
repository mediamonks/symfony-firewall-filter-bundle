<?php

namespace MediaMonks\FirewallFilterBundle\Security\LoginFlow;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Interface CheckAwareInterface
 * @package MediaMonks\FirewallFilterBundle\Security\LoginFlow
 * @author pawel@mediamonks.com
 */
interface CheckAwareInterface
{
    public function onCheck(GetResponseEvent $event);
}