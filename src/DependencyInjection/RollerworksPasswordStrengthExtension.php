<?php

/*
 * This file is part of the RollerworksPasswordStrengthBundle package.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Rollerworks\Bundle\PasswordStrengthBundle\DependencyInjection;

use Rollerworks\Component\PasswordStrength\Blacklist\LazyChainProvider;
use Rollerworks\Component\PasswordStrength\Command\BlacklistListCommand;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\DirectoryResource;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class RollerworksPasswordStrengthExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('rollerworks_password_strength.blacklist_provider', $config['blacklist']['default_provider']);
        $container->setParameter('rollerworks_password_strength.blacklist.sqlite.dsn', '');

        $container->setAlias('rollerworks_password_strength.blacklist_provider', $config['blacklist']['default_provider']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('strength_validator.xml');
        $loader->load('blacklist.xml');

        if (isset($config['blacklist']['providers'])) {
            $this->setBlackListProvidersConfiguration($config['blacklist']['providers'], $container);
            $this->registerBlacklistCommands($container, $config['blacklist']['providers']);
        }
    }

    /**
     * Allow an extension to prepend the extension configurations.
     */
    public function prepend(ContainerBuilder $container): void
    {
        $container->prependExtensionConfig('framework', [
            'translator' => [
                'paths' => [
                    dirname(dirname((new \ReflectionClass(LazyChainProvider::class))->getFileName())).'/Resources/translations',
                ],
            ],
        ]);
    }

    private function setBlackListProvidersConfiguration(array $config, ContainerBuilder $container)
    {
        if (isset($config['sqlite'])) {
            $container->setParameter('rollerworks_password_strength.blacklist.sqlite.dsn', $config['sqlite']['dsn']);
        }

        if (isset($config['array'])) {
            $container
                ->getDefinition('rollerworks_password_strength.blacklist.provider.array')
                ->replaceArgument(0, $config['array']);
        }

        if (isset($config['chain'])) {
            if ($config['chain']['lazy']) {
                $this->configureLazyChainBlacklistProvider($container, $config);
            } else {
                $this->configureChainBlacklistProvider($container, $config);
            }
        }
    }

    private function configureLazyChainBlacklistProvider(ContainerBuilder $container, array $config)
    {
        $refs = [];
        $serviceIds = [];

        foreach ($config['chain']['providers'] as $name => $serviceId) {
            $refs[$serviceId] = new Reference($serviceId);
            $serviceIds[] = $serviceId;
        }

        $chainLoader = $container->getDefinition('rollerworks_password_strength.blacklist.provider.chain');
        $chainLoader->setArguments([ServiceLocatorTagPass::register($container, $refs), $serviceIds]);
        $chainLoader->setClass(LazyChainProvider::class);
    }

    private function configureChainBlacklistProvider(ContainerBuilder $container, array $config)
    {
        $chainLoader = $container->getDefinition('rollerworks_password_strength.blacklist.provider.chain');

        foreach ($config['chain']['providers'] as $provider) {
            $chainLoader->addMethodCall('addProvider', [new Reference($provider)]);
        }
    }

    private function registerBlacklistCommands(ContainerBuilder $container, array $providers)
    {
        if (!class_exists(Application::class)) {
            return;
        }

        $refs = ['default' => new Reference('rollerworks_password_strength.blacklist_provider')];
        foreach ($providers as $name => $serviceId) {
            $refs[$name] = new Reference('rollerworks_password_strength.blacklist.provider.'.$name);
        }
        $providersService = ServiceLocatorTagPass::register($container, $refs);

        $r = new \ReflectionClass(BlacklistListCommand::class);
        $container->addResource(new DirectoryResource(dirname($r->getFileName())));
        $namespace = $r->getNamespaceName();

        $finder = (new Finder())
            ->in(dirname($r->getFileName()))
            ->name('/\.php$/')
            ->notName('/BlacklistCommand.php$/')
            ->notName('/BlacklistCommonCommand\.php$/')
        ;

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $class = $namespace.'\\'.$file->getBasename('.php');

            $container->register($class, $class)
                ->addTag('console.command')
                ->addArgument($providersService)
            ;
        }
    }
}
