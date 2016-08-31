<?php

namespace MediaMonks\FirewallFilterBundle\DependencyInjection;

use MediaMonks\FirewallFilterBundle\DependencyInjection\CompilerPass\OverrideCompiler;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class MediaMonksFirewallFilterExtension extends Extension implements CompilerPassInterface
{
    const FIREWALL_CLASS = "MediaMonks\\FirewallFilterBundle\\Security\\Http\\Firewall";
    const FIREWALL_MAP_CLASS = "MediaMonks\\FirewallFilterBundle\\Security\\FirewallMap";
    const SECURITY_FIREWALL_DEFINITION = 'security.firewall';
    const SECURITY_FIREWALL_MAP_DEFINITION = 'security.firewall.map';

    /**
     * @var array
     */
    protected $config;

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $this->config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    public function process(ContainerBuilder $container)
    {
        if($this->config['add_firewall']){
            if(
                !$container->hasDefinition(self::SECURITY_FIREWALL_DEFINITION)
                || !$container->has(self::SECURITY_FIREWALL_MAP_DEFINITION)
            ) {
                throw new \LogicException("Missing security bundle or firewall or firewall map definition ids changed");
            }

            $container->getDefinition(self::SECURITY_FIREWALL_DEFINITION)->setClass(self::FIREWALL_CLASS);
            $container->getDefinition(self::SECURITY_FIREWALL_MAP_DEFINITION)->setClass(self::FIREWALL_MAP_CLASS);
        }
    }

    public function getAlias()
    {
        return 'mediamonks_security';
    }


}
