<?php

namespace MediaMonks\FirewallFilterBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractContainerBuilderTestCase;
use MediaMonks\FirewallFilterBundle\DependencyInjection\Security\FirewallFilterFactory;

/**
 * Class AbstractFirewallFilterTestCase
 * @package MediaMonks\SecurityBundle\Tests\DependencyInjection
 * @author pawel@mediamonks.com
 */
class AbstractFirewallFilterTestCase extends AbstractContainerBuilderTestCase
{
    protected function registerFirewallFilterServices()
    {
        $this->registerService(FirewallFilterFactory::AUTH_PROVIDER, null);
        $this->registerService(FirewallFilterFactory::AUTH_FIREWALL_LISTENER, null);
    }

    protected function executeFactory($id, $config, $userProviderId, $entryPoint)
    {
        $factory = new FirewallFilterFactory();
        $result = $factory->create($this->container, $id, $config, $userProviderId, $entryPoint);

        return $result;
    }
}
