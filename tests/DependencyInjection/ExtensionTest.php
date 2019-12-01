<?php

/*
 * This file is part of the RollerworksPasswordStrengthBundle package.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Rollerworks\Bundle\PasswordStrengthBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Rollerworks\Bundle\PasswordStrengthBundle\DependencyInjection\RollerworksPasswordStrengthExtension;
use Rollerworks\Component\PasswordStrength\Blacklist\ArrayProvider;
use Rollerworks\Component\PasswordStrength\Blacklist\ChainProvider;
use Rollerworks\Component\PasswordStrength\Blacklist\LazyChainProvider;
use Rollerworks\Component\PasswordStrength\Blacklist\NoopProvider;
use Rollerworks\Component\PasswordStrength\Blacklist\SqliteProvider;
use Rollerworks\Component\PasswordStrength\Command\BlacklistCommand;
use Rollerworks\Component\PasswordStrength\Command\BlacklistCommonCommand;
use Rollerworks\Component\PasswordStrength\Command\BlacklistListCommand;
use Rollerworks\Component\PasswordStrength\Validator\Constraints\Blacklist as BlacklistConstraint;
use Rollerworks\Component\PasswordStrength\Validator\Constraints\BlacklistValidator;
use Rollerworks\Component\PasswordStrength\Validator\Constraints\PasswordStrength;
use Rollerworks\Component\PasswordStrength\Validator\Constraints\PasswordStrengthValidator;
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\Validator\ContainerConstraintValidatorFactory;
use Symfony\Component\Validator\DependencyInjection\AddConstraintValidatorsPass;

class ExtensionTest extends AbstractExtensionTestCase
{
    public function testLoadDefaultConfiguration()
    {
        $this->load();
        $this->initProviderService();
        $this->compile();

        $this->assertContainerBuilderHasService(BlacklistValidator::class);
        $this->assertContainerBuilderHasService('rollerworks_password_strength.blacklist_provider', NoopProvider::class);

        $constraint = new BlacklistConstraint();
        $this->assertContainerBuilderHasService($constraint->validatedBy());
    }

    private function initProviderService(): void
    {
        $this->container->getAlias('rollerworks_password_strength.blacklist_provider')->setPublic(true);
        $this->container->getCompiler()->addPass(new MakeAllServicesPublicPass(), PassConfig::TYPE_OPTIMIZE);
    }

    public function testLoadWithSqliteConfiguration()
    {
        $this->load([
            'blacklist' => [
                'default_provider' => 'rollerworks_password_strength.blacklist.provider.sqlite',
                'providers' => [
                    'sqlite' => ['dsn' => 'sqlite:something'],
                ],
            ],
        ]);

        $this->initProviderService();
        $this->compile();

        $this->assertContainerBuilderHasService(PasswordStrengthValidator::class);
        $this->assertContainerBuilderHasService('rollerworks_password_strength.blacklist_provider', SqliteProvider::class);
    }

    public function testLoadWithArrayConfiguration()
    {
        $this->load([
            'blacklist' => [
                'default_provider' => 'rollerworks_password_strength.blacklist.provider.array',
                'providers' => [
                    'array' => ['foo', 'foobar', 'kaboom'],
                ],
            ],
        ]);

        $this->initProviderService();
        $this->compile();

        $this->assertContainerBuilderHasService(PasswordStrengthValidator::class);
        $this->assertContainerBuilderHasService('rollerworks_password_strength.blacklist_provider', ArrayProvider::class);

        $provider = $this->container->get('rollerworks_password_strength.blacklist_provider');

        self::assertTrue($provider->isBlacklisted('foo'));
        self::assertTrue($provider->isBlacklisted('foobar'));
        self::assertTrue($provider->isBlacklisted('kaboom'));
        self::assertFalse($provider->isBlacklisted('leeRoy'));
    }

    public function testLoadWithChainConfiguration()
    {
        $this->load([
            'blacklist' => [
                'default_provider' => 'rollerworks_password_strength.blacklist.provider.chain',
                'providers' => [
                    'array' => ['foo', 'foobar', 'kaboom'],
                    'chain' => [
                        'providers' => [
                            'rollerworks_password_strength.blacklist.provider.array',
                            'acme.password_blacklist.array',
                        ],
                    ],
                ],
            ],
        ]);

        $this->container->set(
            'acme.password_blacklist.array',
            new ArrayProvider(['amy', 'doctor', 'rory'])
        );

        $this->initProviderService();
        $this->compile();

        $this->assertContainerBuilderHasService(PasswordStrengthValidator::class);
        $this->assertContainerBuilderHasService('rollerworks_password_strength.blacklist_provider', ChainProvider::class);

        $provider = $this->container->get('rollerworks_password_strength.blacklist_provider');
        self::assertTrue($provider->isBlacklisted('foo'));
        self::assertTrue($provider->isBlacklisted('foobar'));
        self::assertTrue($provider->isBlacklisted('kaboom'));
        self::assertTrue($provider->isBlacklisted('doctor'));
        self::assertFalse($provider->isBlacklisted('leeRoy'));
    }

    public function testLoadWithLazyChainConfiguration()
    {
        $this->load([
            'blacklist' => [
                'default_provider' => 'rollerworks_password_strength.blacklist.provider.chain',
                'providers' => [
                    'array' => ['foo', 'foobar', 'kaboom'],
                    'chain' => [
                        'lazy' => true,
                        'providers' => [
                            'rollerworks_password_strength.blacklist.provider.array',
                            'acme.password_blacklist.array',
                        ],
                    ],
                ],
            ],
        ]);

        $this->container->set(
            'acme.password_blacklist.array',
            new ArrayProvider(['amy', 'doctor', 'rory'])
        );

        $this->initProviderService();
        $this->compile();

        $this->assertContainerBuilderHasService(PasswordStrengthValidator::class);
        $this->assertContainerBuilderHasService('rollerworks_password_strength.blacklist_provider', LazyChainProvider::class);

        $provider = $this->container->get('rollerworks_password_strength.blacklist_provider');
        self::assertTrue($provider->isBlacklisted('foo'));
        self::assertTrue($provider->isBlacklisted('foobar'));
        self::assertTrue($provider->isBlacklisted('kaboom'));
        self::assertTrue($provider->isBlacklisted('doctor'));
        self::assertFalse($provider->isBlacklisted('leeRoy'));
    }

    public function testPasswordValidatorsAreRegistered()
    {
        $this->container->addCompilerPass(new AddConstraintValidatorsPass());
        $this->container->register('validator.validator_factory', ContainerConstraintValidatorFactory::class)
            ->setPublic(true)
            ->setArguments([new Reference('service_container'), []]);

        $this->load();
        $this->initProviderService();
        $this->compile();

        /** @var ContainerConstraintValidatorFactory $factory */
        $factory = $this->container->get('validator.validator_factory');

        self::assertInstanceOf(PasswordStrengthValidator::class, $factory->getInstance(new PasswordStrength(['minStrength' => 1])));
        self::assertInstanceOf(BlacklistValidator::class, $factory->getInstance(new BlacklistConstraint()));
    }

    public function testBlacklistCommandsAreRegistered()
    {
        if (!class_exists(Application::class)) {
            $this->markTestSkipped('Needs the Symfony/console component');
        }

        $this->container->set(
            'acme.password_blacklist.array',
            new ArrayProvider(['amy', 'doctor', 'rory'])
        );

        $this->load([
            'blacklist' => [
                'default_provider' => 'rollerworks_password_strength.blacklist.provider.chain',
                'providers' => [
                    'array' => ['foo', 'foobar', 'kaboom'],
                    'chain' => [
                        'providers' => [
                            'rollerworks_password_strength.blacklist.provider.array',
                            'acme.password_blacklist.array',
                        ],
                    ],
                ],
            ],
        ]);
        $this->initProviderService();
        $this->compile();

        // No need to test all commands.
        $this->assertContainerBuilderHasServiceDefinitionWithTag(BlacklistListCommand::class, 'console.command');
        $this->assertContainerBuilderNotHasService(BlacklistCommand::class);
        $this->assertContainerBuilderNotHasService(BlacklistCommonCommand::class);
        $command = $this->container->findDefinition(BlacklistListCommand::class);
        /** @var ServiceLocator $argument */
        $argument = $this->container->get((string) $command->getArgument(0));

        self::assertTrue($argument->has('default'), 'Should have "default" as provider');
        self::assertTrue($argument->has('array'), 'Should have "array" as provider');
        self::assertFalse($argument->has('container'), 'Should not have "container" as provider');
    }

    protected function getContainerExtensions(): array
    {
        return [
            new RollerworksPasswordStrengthExtension(),
        ];
    }
}
