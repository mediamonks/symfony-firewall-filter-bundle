<?php

namespace MediaMonks\FirewallFilterBundle\Security\Listener;

use MediaMonks\FirewallFilterBundle\Security\Http\Firewall;
use MediaMonks\FirewallFilterBundle\Security\LoginFlow\LoginAwareInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Class GuardianListener
 * @package MediaMonks\FirewallFilterBundle\Security\Listener
 * @author pawel@mediamonks.com
 */
class GuardianLoginListener
{
    /**
     * @var LoginAwareInterface[]
     */
    protected $handlers = [];

    /**
     * @param $firewall
     * @param LoginAwareInterface $handler
     */
    public function addHandler($firewall, LoginAwareInterface $handler)
    {
        if(!isset($this->handlers[$firewall])){
            $this->handlers[$firewall] = [];
        }

        $this->handlers[$firewall][] = $handler;
    }

    /**
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $currentFirewall = $event->getRequest()->attributes->get(Firewall::CURRENT_FIREWALL_KEY);

        if(!$currentFirewall || !isset($this->handlers[$currentFirewall])){
            return;
        }

        foreach($this->handlers[$currentFirewall] as $handler){
            $handler->onLogin($event);
        }
    }
}