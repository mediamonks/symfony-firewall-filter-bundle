<?php

namespace MediaMonks\FirewallFilterBundle\Security\LoginFlow;

/**
 * Interface CheckAwareInterface
 * @package MediaMonks\FirewallFilterBundle\Security\LoginFlow
 * @author pawel@mediamonks.com
 */
interface CheckAwareInterface
{
    public function onCheck($event);
}