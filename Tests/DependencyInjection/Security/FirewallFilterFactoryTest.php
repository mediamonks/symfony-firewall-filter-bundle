<?php

namespace MediaMonks\FirewallFilterBundle\Tests\DependencyInjection\Security;

use MediaMonks\FirewallFilterBundle\DependencyInjection\Security\FirewallFilterFactory;
use MediaMonks\FirewallFilterBundle\Tests\DependencyInjection\AbstractFirewallFilterTestCase;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class GuardianFactoryTest
 * @package MediaMonks\SecurityBundle\Tests\DependencyInjection\Security
 * @author pawel@mediamonks.com
 */
class FirewallFilterFactoryTest extends AbstractFirewallFilterTestCase
{
    /**
     * @dataProvider testCreateDataProvider
     */
    public function testCreate()
    {
        $data = func_get_args();
        $expectedParam = [];
        foreach($data as $row){
            list($id, $config, $userProviderId, $entryPoint) = array_values($row);
            $expectedParam[$id] = $config['handler'];

            $logoutService = FirewallFilterFactory::SYMFONY_LOGOUT_LISTENER . '.' . $id;

            $this->registerFirewallFilterServices();
            $this->registerService($logoutService, null);

            list($authProviderId, $authListenerId, $defaultEntryPoint) = $this->executeFactory($id, $config, $userProviderId, $entryPoint);

            $this->assertEquals($defaultEntryPoint, $entryPoint);
            $this->assertContainerBuilderHasService($authProviderId);
            $this->assertContainerBuilderHasService($authListenerId);

            $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
                $logoutService,
                'addHandler', [ new Reference(FirewallFilterFactory::getLogoutHandlerName($id)) ]
            );
        }

        $this->assertContainerBuilderHasParameter(FirewallFilterFactory::DATA_PARAMETER, $expectedParam);
    }


    public function testCreateDataProvider()
    {
        return
        [
            //Input 1
            [
                [
                    'id' => 'test',
                    'config' => [ 'handler' => [] ],
                    'userProviderId' => 'test',
                    'entryPoint' => 'entrypoint',
                ]
            ],
            //Input 2
            [
                [
                    'id' => 'test',
                    'config' => [ 'handler' => [] ],
                    'userProviderId' => 'test',
                    'entryPoint' => 'entrypoint',
                ],
                [
                    'id' => 'test2',
                    'config' => [ 'handler' => [] ],
                    'userProviderId' => 'test2',
                    'entryPoint' => 'entrypoint2',
                ]
            ]
        ];
    }
}
