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

use Rollerworks\Component\PasswordStrength\Blacklist\SqliteProvider as BaseSqliteProvider;

@trigger_error(sprintf('The %s class is deprecated since version 1.7 and will be removed in 2.0. Use %s instead.', SqliteProvider::class, BaseSqliteProvider::class), E_USER_DEPRECATED);

/**
 * Sqlite Blacklist Provider.
 *
 * @author Sebastiaan Stok <s.stok@rollerscapes.net>
 * @author Fabien Potencier
 *
 * @deprecated since 1.7, to be removed in 2.0. Use {@link BaseSqliteProvider} instead.
 */
class SqliteProvider extends BaseSqliteProvider implements UpdatableBlacklistProviderInterface
{
}