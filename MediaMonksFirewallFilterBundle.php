<?php

namespace MediaMonks\FirewallFilterBundle;

use MediaMonks\FirewallFilterBundle\DependencyInjection\CompilerPass\FilterFlowCompiler;
use MediaMonks\FirewallFilterBundle\DependencyInjection\CompilerPass\OverrideCompiler;
use MediaMonks\FirewallFilterBundle\DependencyInjection\MediaMonksFirewallFilterExtension;
use MediaMonks\FirewallFilterBundle\DependencyInjection\Security\FirewallFilterFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MediaMonksFirewallFilterBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new FirewallFilterFactory());

        $container->addCompilerPass(new FilterFlowCompiler());
    }

    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new MediaMonksFirewallFilterExtension();
        }

        return $this->extension;
    }


    public function getParent()
    {
        return 'SecurityBundle';
    }
}
