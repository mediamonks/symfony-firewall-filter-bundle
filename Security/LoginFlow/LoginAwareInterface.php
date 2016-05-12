<?php

namespace MediaMonks\SecurityBundle\Security\LoginFlow;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Interface LoginAwareInterface
 * @package MediaMonks\SecurityBundle\Security\LoginFlow
 * @author pawel@mediamonks.com
 */
interface LoginAwareInterface
{
    public function onLogin(InteractiveLoginEvent $event);
}