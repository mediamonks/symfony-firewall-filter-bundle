<?php

namespace MediaMonks\FirewallFilterBundle\Tests\Functional\app;

use MediaMonks\FirewallFilterBundle\Security\LoginFlow\CheckAwareInterface;
use MediaMonks\FirewallFilterBundle\Security\LoginFlow\LoginAwareInterface;
use MediaMonks\FirewallFilterBundle\Security\LoginFlow\LogoutAwareInterface;
use \Mockery as m;

/**
 * Class HandlerFactory in package MediaMonks\FirewallFilterBundle\Tests\Functional\app
 *
 * @author pawel@mediamonks.com
 */
class HandlerFactory
{
    public function createLoginAware()
    {
        static $ret;
        if($ret == null){
            $ret = m::mock(LoginAwareInterface::class);
        }
        return $ret;
    }

    public function createCheckAware()
    {
        static $ret;
        if($ret == null){
            $ret = m::mock(CheckAwareInterface::class);
        }
        return $ret;
    }

    public function createLogoutAware()
    {
        static $ret;
        if($ret == null){
            $ret = m::mock(LogoutAwareInterface::class);
        }
        return $ret;
    }
}