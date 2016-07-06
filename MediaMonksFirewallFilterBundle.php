<?php

namespace MediaMonks\FirewallFilterBundle;

use MediaMonks\FirewallFilterBundle\DependencyInjection\CompilerPass\LoginFlowCompiler;
use MediaMonks\FirewallFilterBundle\DependencyInjection\Security\FirewallFilterFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MediaMonksFirewallFilterBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new FirewallFilterFactory());

        $container->addCompilerPass(new LoginFlowCompiler());
    }

    public function getParent()
    {
        return 'SecurityBundle';
    }
}
