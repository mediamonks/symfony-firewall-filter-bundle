<?php

namespace MediaMonks\FirewallFilterBundle;

use MediaMonks\FirewallFilterBundle\DependencyInjection\CompilerPass\LoginFlowCompiler;
use MediaMonks\FirewallFilterBundle\DependencyInjection\Security\GuardianFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MediaMonksFirewallFilterBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new GuardianFactory());

        $container->addCompilerPass(new LoginFlowCompiler());
    }

    public function getParent()
    {
        return 'SecurityBundle';
    }
}
