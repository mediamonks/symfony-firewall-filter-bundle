<?php

namespace MediaMonks\FirewallFilterBundle\Tests\Functional\app\Handlers;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class TestController in package MediaMonks\FirewallFilterBundle\Tests\Functional\app\Handlers
 *
 * @author pawel@mediamonks.com
 */
class TestController
{
    public function testAction()
    {
        return new Response();
    }

    public function anotherTestAction()
    {
        return new Response();
    }

    public function notRelevantAction()
    {
        return new Response();
    }
}