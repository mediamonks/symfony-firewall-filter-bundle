<?php

namespace MediaMonks\SecurityBundle\Security\Firewall;

use MediaMonks\SecurityBundle\Security\LoginFlow\CheckAwareInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

/**
 * Class GuardianListener
 * @package MediaMonks\SecurityBundle\Security\Firewall
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