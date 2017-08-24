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

use Rollerworks\Component\PasswordStrength\Blacklist\PdoProvider as BasePdoProvider;

@trigger_error(sprintf('The %s class is deprecated since version 1.7 and will be removed in 2.0. Use %s instead.', PdoProvider::class, BasePdoProvider::class), E_USER_DEPRECATED);

/**
 * Sqlite Blacklist Provider.
 *
 * @author Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * @deprecated since 1.7, to be removed in 2.0. Use {@link BasePdoProvider} instead.
 */
abstract class PdoProvider extends BasePdoProvider implements UpdatableBlacklistProviderInterface
{
}
