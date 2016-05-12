<?php

namespace MediaMonks\SecurityBundle;

use MediaMonks\SecurityBundle\Security\LoginFlow\CheckAwareInterface;
use MediaMonks\SecurityBundle\Security\LoginFlow\LoginAwareInterface;
use MediaMonks\SecurityBundle\Security\LoginFlow\LogoutAwareInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

/**
 * Class MyEventListener
 * @package MediaMonks\SecurityBundle
 * @author pawel@mediamonks.com
 */
class MyEventListener implements CheckAwareInterface, LoginAwareInterface, LogoutAwareInterface
{
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        dump($event); die;
    }

    public function onCheck($event)
    {

    }

    public function onLogin(InteractiveLoginEvent $event)
    {

    }

    public function onLogout(Request $request, Response $response, TokenInterface $token)
    {

    }
}