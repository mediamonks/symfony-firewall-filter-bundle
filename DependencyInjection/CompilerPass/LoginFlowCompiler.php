<?php

namespace MediaMonks\SecurityBundle\DependencyInjection\CompilerPass;

use MediaMonks\SecurityBundle\DependencyInjection\Security\GuardianFactory;
use MediaMonks\SecurityBundle\Security\LoginFlow\CheckAwareInterface;
use MediaMonks\SecurityBundle\Security\LoginFlow\LoginAwareInterface;
use MediaMonks\SecurityBundle\Security\LoginFlow\LogoutAwareInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Class LoginFlowCompiler
 * @package MediaMonks\SecurityBundle\DependencyInjection\CompilerPass
 * @author pawel@mediamonks.com
 */
class LoginFlowCompiler implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if(!$container->hasParameter(GuardianFactory::GUARDIAN_PARAMETER)){
            return;
        }

        foreach($container->getParameter(GuardianFactory::GUARDIAN_PARAMETER) as $firewall => $handlers){
            $this->processFirewall($container, $firewall, $handlers);
        }
    }

    protected function processFirewall(ContainerBuilder $container, $firewall, $handlers)
    {
        $login = $container->getDefinition(GuardianFactory::AUTH_CHECK_LISTENER);
        $check = $container->getDefinition(GuardianFactory::getFirewallListenerName($firewall));
        $logout = $container->getDefinition(GuardianFactory::getLogoutHandlerName($firewall));

        $handlers = array_flip($handlers);
        array_walk($handlers, function(&$item, $key){
            $item = [];
        });

        $handlers = array_merge($handlers, $container->findTaggedServiceIds('guardian.' . $firewall));

        foreach($handlers as $handler => $params){
            $this->processHandler(
                $container->getDefinition($handler), $login, $check, $logout, $firewall
            );
        }
    }

    public function processHandler(Definition $handler, Definition $login, Definition $check, Definition $logout, $firewall)
    {
        $interfaces = class_implements($handler->getClass());

        if(isset($interfaces[LoginAwareInterface::class])){
            $login->addMethodCall(
                'addHandler', [$firewall, $handler]
            );
        }

        if(isset($interfaces[CheckAwareInterface::class])){
            $check->addMethodCall(
                'addHandler', [$handler]
            );
        }

        if(isset($interfaces[LogoutAwareInterface::class])){
            $logout->addMethodCall(
                'addHandler', [$handler]
            );
        }
    }
}