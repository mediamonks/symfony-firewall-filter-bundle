<?php

namespace MediaMonks\SecurityBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractContainerBuilderTestCase;
use MediaMonks\SecurityBundle\DependencyInjection\Security\GuardianFactory;

/**
 * Class AbstractGuardianTestCase
 * @package MediaMonks\SecurityBundle\Tests\DependencyInjection
 * @author pawel@mediamonks.com
 */
class AbstractGuardianTestCase extends AbstractContainerBuilderTestCase
{
    protected function registerGuardianServices()
    {
        $this->registerService(GuardianFactory::AUTH_PROVIDER, null);
        $this->registerService(GuardianFactory::AUTH_FIREWALL_LISTENER, null);
    }

    protected function executeFactory($id, $config, $userProviderId, $entryPoint)
    {
        $factory = new GuardianFactory();
        $result = $factory->create($this->container, $id, $config, $userProviderId, $entryPoint);

        return $result;
    }
}
