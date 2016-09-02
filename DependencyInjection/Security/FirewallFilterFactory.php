<?php

namespace MediaMonks\FirewallFilterBundle\DependencyInjection\Security;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;


/**
 * Class FirewallFilterFactory
 * Security extension. Defines "event" handlers for each firewall with "firewall_filter" attribute.
 * Besides it gathers parameters that are later used byt FilterFlowPass.
 *
 * @package MediaMonks\FirewallFilterBundle\DependencyInjection\Security
 * @author pawel@mediamonks.com
 */
class FirewallFilterFactory implements SecurityFactoryInterface
{
    const DATA_PARAMETER = 'firewall_filter_params';

    const AUTH_CHECK_LISTENER = 'media_monks.firewall_filter.auth_listener';
    const AUTH_FIREWALL_LISTENER = 'media_monks.firewall_filter.firewall_listener';
    const AUTH_LOGOUT_HANDLER = 'media_monks.firewall_filter.logout_handler';
    const AUTH_PROVIDER = 'media_monks.firewall_filter.authentication_provider';
    const SYMFONY_LOGOUT_LISTENER = 'security.logout_listener';

    public function getPosition()
    {
        return 'http';
    }

    public function getKey()
    {
        return 'firewall_filter';
    }

    public function addConfiguration(NodeDefinition $builder)
    {
        $builder
            ->children()
            ->arrayNode('handlers')
            ->info('An array of service ids for all of your "handlers"')
            ->prototype('scalar')->end()
            ->end()
        ;
    }

    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        //Auth provider
        $authProviderId = static::AUTH_PROVIDER . '.' . $id;
        $container
            ->setDefinition($authProviderId, new DefinitionDecorator(static::AUTH_PROVIDER));

        //Auth listener
        $authListenerId = static::getFirewallListenerName($id);
        $container
            ->setDefinition($authListenerId, new DefinitionDecorator(static::AUTH_FIREWALL_LISTENER));

        //Logout handler
        if($container->hasDefinition(self::SYMFONY_LOGOUT_LISTENER . '.' . $id)){
            $authLogoutId = static::getLogoutHandlerName($id);
            $container
                ->setDefinition($authLogoutId, new DefinitionDecorator(static::AUTH_LOGOUT_HANDLER));
            $container->getDefinition(self::SYMFONY_LOGOUT_LISTENER . '.' . $id)
                ->addMethodCall('addHandler', [ new Reference($authLogoutId) ]);
        }

        $this->addForCompiler($container, $id, $config['handlers']);

        return [
            $authProviderId,
            $authListenerId,
            $defaultEntryPoint
        ];
    }

    /**
     * Add handlers to list that will be processed in compiler
     * @param ContainerBuilder $builder
     * @param $id
     * @param $handlers
     */
    protected function addForCompiler(ContainerBuilder $builder, $id, $handlers)
    {
        $toMerge = [];
        if($builder->hasParameter(self::DATA_PARAMETER)){
            $toMerge = $builder->getParameter(self::DATA_PARAMETER);
        }

        $toMerge[$id] = $handlers;

        $builder->setParameter(self::DATA_PARAMETER, $toMerge);
    }

    /**
     * Return name of certain firewall listener
     * @param $id
     * @return string
     */
    public static function getFirewallListenerName($id)
    {
        return static::AUTH_FIREWALL_LISTENER . '.' . $id;
    }

    /**
     * Return name of certain firewall logout listener
     * @param $id
     * @return string
     */
    public static function getLogoutHandlerName($id)
    {
        return static::AUTH_LOGOUT_HANDLER . '.' . $id;
    }
}