<?php

namespace MediaMonks\FirewallFilterBundle\DependencyInjection\CompilerPass;

use MediaMonks\FirewallFilterBundle\DependencyInjection\Security\FirewallFilterFactory;
use MediaMonks\FirewallFilterBundle\Security\LoginFlow\CheckAwareInterface;
use MediaMonks\FirewallFilterBundle\Security\LoginFlow\LoginAwareInterface;
use MediaMonks\FirewallFilterBundle\Security\LoginFlow\LogoutAwareInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Class FilterFlowCompiler
 * @package MediaMonks\FirewallFilterBundle\DependencyInjection\CompilerPass
 * @author pawel@mediamonks.com
 */
class FilterFlowCompiler implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if(!$container->hasParameter(FirewallFilterFactory::DATA_PARAMETER)){
            return;
        }

        foreach($container->getParameter(FirewallFilterFactory::DATA_PARAMETER) as $firewall => $handlers){
            $this->processFirewall($container, $firewall, $handlers);
        }
    }

    protected function processFirewall(ContainerBuilder $container, $firewall, $handlers)
    {
        //Interactive login
        $login = $container->getDefinition(FirewallFilterFactory::AUTH_CHECK_LISTENER);
        //Firewall listener
        $check = $container->getDefinition(FirewallFilterFactory::getFirewallListenerName($firewall));
        //Logout listener
        $logout = $container->getDefinition(FirewallFilterFactory::getLogoutHandlerName($firewall));

        $handlers = array_flip($handlers);
        array_walk($handlers, function(&$item, $key){
            $item = [];
        });

        $handlers = array_merge($handlers, $container->findTaggedServiceIds('firewall_filter.' . $firewall));

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