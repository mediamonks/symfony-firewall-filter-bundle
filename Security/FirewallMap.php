<?php

namespace MediaMonks\SecurityBundle\Security;

use Symfony\Bundle\SecurityBundle\Security\FirewallMap as FirewallMapBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FirewallMap
 * @package MediaMonks\SecurityBundle\Security
 * @author pawel@mediamonks.com
 */
class FirewallMap extends FirewallMapBase
{
    protected $lastPickedFirewall;

    /**
     * @return mixed
     */
    public function getLastPickedFirewall()
    {
        return $this->lastPickedFirewall;
    }

    public function getListeners(Request $request)
    {
        foreach ($this->map as $contextId => $requestMatcher) {
            if (null === $requestMatcher || $requestMatcher->matches($request)) {

                $parts = explode('.', $contextId);
                $this->lastPickedFirewall = end($parts);

                return $this->container->get($contextId)->getContext();
            }
        }

        return array(array(), null);
    }
}