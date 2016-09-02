<?php

namespace MediaMonks\FirewallFilterBundle\DependencyInjection\CompilerPass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class OverridePass in package MediaMonks\FirewallFilterBundle\DependencyInjection\CompilerPass
 * Overrides Firewall and FirewallMap. Thanks to new implementation request attribute "_current_firewall"
 * is available. It provides current firewall name.
 *
 * @author pawel@mediamonks.com
 */
class OverridePass implements CompilerPassInterface
{
    const FIREWALL_CLASS = "MediaMonks\\FirewallFilterBundle\\Security\\Http\\Firewall";
    const FIREWALL_MAP_CLASS = "MediaMonks\\FirewallFilterBundle\\Security\\FirewallMap";
    const SECURITY_FIREWALL_DEFINITION = 'security.firewall';
    const SECURITY_FIREWALL_MAP_DEFINITION = 'security.firewall.map';

    public function process(ContainerBuilder $container)
    {
        if (
            !$container->hasDefinition(self::SECURITY_FIREWALL_DEFINITION)
            || !$container->has(self::SECURITY_FIREWALL_MAP_DEFINITION)
        ) {
            throw new \LogicException("Missing security bundle or firewall or firewall map definition ids changed");
        }

        $container->getDefinition(self::SECURITY_FIREWALL_DEFINITION)->setClass(self::FIREWALL_CLASS);
        $container->getDefinition(self::SECURITY_FIREWALL_MAP_DEFINITION)->setClass(self::FIREWALL_MAP_CLASS);
    }
}
