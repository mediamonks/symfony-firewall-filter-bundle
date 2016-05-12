<?php

namespace MediaMonks\SecurityBundle\DependencyInjection\Security;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Reference;


/**
 * Class GuardianFactory
 * @package MediaMonks\SecurityBundle\DependencyInjection\Security
 * @author pawel@mediamonks.com
 */
class GuardianFactory implements SecurityFactoryInterface
{
    const GUARDIAN_PARAMETER = 'guardian_params';

    const AUTH_CHECK_LISTENER = 'media_monks.guardian.listener';
    const AUTH_FIREWALL_LISTENER = 'media_monks.guardian.firewall_listener';
    const AUTH_LOGOUT_HANDLER = 'media_monks.guardian.logout_handler';
    const AUTH_PROVIDER = 'media_monks.guardian.authentication_provider';
    const SYMFONY_LOGOUT_LISTENER = 'security.logout_listener';

    public function getPosition()
    {
        return 'http';
    }

    public function getKey()
    {
        return 'guardian';
    }

    public function addConfiguration(NodeDefinition $builder)
    {
        $builder
            ->children()
            ->arrayNode('handler')
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
        $authLogoutId = static::getLogoutHandlerName($id);
        $container
            ->setDefinition($authLogoutId, new DefinitionDecorator(static::AUTH_LOGOUT_HANDLER));
        $container->getDefinition(self::SYMFONY_LOGOUT_LISTENER . '.' . $id)
            ->addMethodCall('addHandler', [ new Reference($authLogoutId) ]);

        $this->addForCompiler($container, $id, $config['handler']);

        return [
            $authProviderId,
            $authListenerId,
            $defaultEntryPoint
        ];
    }

    protected function addForCompiler(ContainerBuilder $builder, $id, $handlers)
    {
        $toMerge = [];
        if($builder->hasParameter(self::GUARDIAN_PARAMETER)){
            $toMerge = $builder->getParameter(self::GUARDIAN_PARAMETER);
        }

        $toMerge[$id] = $handlers;

        $builder->setParameter(self::GUARDIAN_PARAMETER, $toMerge);
    }

    public static function getFirewallListenerName($id)
    {
        return static::AUTH_FIREWALL_LISTENER . '.' . $id;
    }

    public static function getLogoutHandlerName($id)
    {
        return static::AUTH_LOGOUT_HANDLER . '.' . $id;
    }
}