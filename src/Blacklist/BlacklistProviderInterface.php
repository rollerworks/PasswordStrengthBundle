<?php

/*
 * This file is part of the RollerworksPasswordStrengthBundle package.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Rollerworks\Bundle\PasswordStrengthBundle\Blacklist;

use Rollerworks\Component\PasswordStrength\Blacklist\BlacklistProviderInterface as BaseBlacklistProviderInterface;

@trigger_error(sprintf('The %s is deprecated since version 1.7 and will be removed in 2.0. Use %s instead.', BlacklistProviderInterface::class, BaseBlacklistProviderInterface::class), E_USER_DEPRECATED);

/**
 * Blacklist Provider Interface.
 *
 * @author Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * @deprecated since 1.7, to be removed in 2.0. Use {@link BaseBlacklistProviderInterface} instead.
 */
interface BlacklistProviderInterface extends BaseBlacklistProviderInterface
{
}
