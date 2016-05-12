<?php

namespace MediaMonks\SecurityBundle;

use MediaMonks\SecurityBundle\DependencyInjection\CompilerPass\LoginFlowCompiler;
use MediaMonks\SecurityBundle\DependencyInjection\Security\GuardianFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MediaMonksSecurityBundle extends Bundle
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
