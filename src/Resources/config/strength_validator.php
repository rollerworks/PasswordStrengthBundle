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

use Rollerworks\Component\PasswordStrength\Validator\Constraints\PasswordStrengthValidator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $di = $container->services();

    $di->set(PasswordStrengthValidator::class)
        ->args([service('translator')->nullOnInvalid()])
        ->tag('validator.constraint_validator');
};
