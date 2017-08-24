<?php

/*
 * This file is part of the RollerworksPasswordStrengthBundle package.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Rollerworks\Bundle\PasswordStrengthBundle\Validator\Constraints;

use Rollerworks\Component\PasswordStrength\Validator\Constraints\Blacklist as BaseBlacklist;
use Symfony\Component\Validator\Constraint;

@trigger_error(sprintf('The %s class is deprecated since version 1.7 and will be removed in 2.0. Use %s instead.', Blacklist::class, BaseBlacklist::class), E_USER_DEPRECATED);

/**
 * @Annotation
 *
 * @deprecated since 1.7, to be removed in 2.0. Use {@link \Rollerworks\Component\PasswordStrength\Validator\Constraints\Blacklist} instead.
 */
class Blacklist extends BaseBlacklist
{
    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'rollerworks_password_strength.blacklist.validator';
    }
}
