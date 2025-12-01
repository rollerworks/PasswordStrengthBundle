<?php

declare(strict_types=1);

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
use Rollerworks\Component\PasswordStrength\Validator\Constraints\PasswordStrength;
use Rollerworks\Component\PasswordStrength\Validator\Constraints\PasswordStrengthValidator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Validator\ContainerConstraintValidatorFactory;
use Symfony\Component\Validator\DependencyInjection\AddConstraintValidatorsPass;

/**
 * @internal
 */
final class ExtensionTest extends AbstractExtensionTestCase
{
    public function test_password_validators_are_registered()
    {
        $this->container->addCompilerPass(new AddConstraintValidatorsPass());
        $this->container->register('validator.validator_factory', ContainerConstraintValidatorFactory::class)
            ->setPublic(true)
            ->setArguments([new Reference('service_container'), []]);

        $this->load();
        $this->compile();

        /** @var ContainerConstraintValidatorFactory $factory */
        $factory = $this->container->get('validator.validator_factory');

        self::assertInstanceOf(PasswordStrengthValidator::class, $factory->getInstance(new PasswordStrength(minStrength: 1)));
    }

    protected function getContainerExtensions(): array
    {
        return [
            new RollerworksPasswordStrengthExtension(),
        ];
    }
}
