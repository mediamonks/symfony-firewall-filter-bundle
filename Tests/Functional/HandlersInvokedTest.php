<?php

namespace MediaMonks\FirewallFilterBundle\Tests\Functional;

use Mockery as m;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class HandlersInvokedTest in package MediaMonks\FirewallFilterBundle\Tests\DependencyInjection\Functional
 *
 * @author pawel@mediamonks.com
 */
class HandlersInvokedTest extends AbstractFunctionalTestCase
{
    protected $testCase = 'Handlers';

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }

    public function testSomething()
    {
        $kernel = $this->createKernel([
            'root_config' => __DIR__ . '/app/Handlers/config.yml'
        ]);

        $kernel->boot();

        /** @var m\Mock $testService */
        $testService = $kernel->getContainer()->get('test.checkaware');
        $testService
            ->shouldReceive('onCheck')
            ->with(m::type(GetResponseEvent::class))
            ->once();

        $class = get_class($testService);
        $test = new $class;

        $client = $kernel->getContainer()->get('test.client');

        $response = $client->request('GET', '/test');
    }
}