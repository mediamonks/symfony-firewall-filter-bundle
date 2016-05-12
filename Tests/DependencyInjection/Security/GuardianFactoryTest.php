<?php

namespace MediaMonks\SecurityBundle\Tests\DependencyInjection\Security;

use MediaMonks\SecurityBundle\DependencyInjection\Security\GuardianFactory;
use MediaMonks\SecurityBundle\Tests\DependencyInjection\AbstractGuardianTestCase;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class GuardianFactoryTest
 * @package MediaMonks\SecurityBundle\Tests\DependencyInjection\Security
 * @author pawel@mediamonks.com
 */
class GuardianFactoryTest extends AbstractGuardianTestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function atestCreate()
    {
        $id = 'test';
        $config = [];
        $userProviderId = 'test';
        $entryPoint = 'entrypoint';
        $logoutService = GuardianFactory::SYMFONY_LOGOUT_LISTENER . '.' . $id;

        $this->registerGuardianServices();
        $this->registerService($logoutService, null);

        list($authProviderId, $authListenerId, $defaultEntryPoint) = $this->executeFactory($id, $config, $userProviderId, $entryPoint);

        $this->assertEquals($defaultEntryPoint, $entryPoint);
        $this->assertContainerBuilderHasService($authProviderId);
        $this->assertContainerBuilderHasService($authListenerId);
        $this->assertContainerBuilderHasParameter(GuardianFactory::GUARDIAN_PARAMETER, [
            $id
        ]);

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            $logoutService,
            'addHandler', [ new Reference(GuardianFactory::getLogoutHandlerName($id)) ]
        );
    }

    /**
     * @dataProvider testCreate2DataProvider
     */
    public function testCreate2()
    {
        $data = func_get_args();
        $expectedParam = [];
        foreach($data as $row){
            list($id, $config, $userProviderId, $entryPoint) = array_values($row);
            $expectedParam[] = $id;

            $logoutService = GuardianFactory::SYMFONY_LOGOUT_LISTENER . '.' . $id;

            $this->registerGuardianServices();
            $this->registerService($logoutService, null);

            list($authProviderId, $authListenerId, $defaultEntryPoint) = $this->executeFactory($id, $config, $userProviderId, $entryPoint);

            $this->assertEquals($defaultEntryPoint, $entryPoint);
            $this->assertContainerBuilderHasService($authProviderId);
            $this->assertContainerBuilderHasService($authListenerId);
            $this->assertContainerBuilderHasParameter(GuardianFactory::GUARDIAN_PARAMETER, $expectedParam);

            $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
                $logoutService,
                'addHandler', [ new Reference(GuardianFactory::getLogoutHandlerName($id)) ]
            );
        }
    }


    public function testCreate2DataProvider()
    {
        return
        [
            //Input 1
            [
                [
                    'id' => 'test',
                    'config' => [],
                    'userProviderId' => 'test',
                    'entryPoint' => 'entrypoint',
                ]
            ],
            //Input 2
            [
                [
                    'id' => 'test',
                    'config' => [],
                    'userProviderId' => 'test',
                    'entryPoint' => 'entrypoint',
                ],
                [
                    'id' => 'test2',
                    'config' => [],
                    'userProviderId' => 'test2',
                    'entryPoint' => 'entrypoint2',
                ]
            ]
        ];
    }
}
