<?php

namespace MediaMonks\FirewallFilterBundle\Security\Firewall;

use MediaMonks\FirewallFilterBundle\Security\LoginFlow\CheckAwareInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

/**
 * Class GuardianListener
 * @package MediaMonks\FirewallFilterBundle\Security\Firewall
 * @author pawel@mediamonks.com
 */
class GuardianListener implements ListenerInterface
{
    /**
     * @var CheckAwareInterface[]
     */
    protected $handlers = [];

    /**
     * @param CheckAwareInterface $handler
     */
    public function addHandler(CheckAwareInterface $handler)
    {
        $this->handlers[] = $handler;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function handle(GetResponseEvent $event)
    {
        foreach($this->handlers as $handler){
            $handler->onCheck($event);
        }
    }
}