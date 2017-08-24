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

use Rollerworks\Component\PasswordStrength\Validator\Constraints\PasswordStrength as BasePasswordStrength;

@trigger_error(sprintf('The %s class is deprecated since version 1.7 and will be removed in 2.0. Use %s instead.', PasswordStrength::class, BasePasswordStrength::class), E_USER_DEPRECATED);

/**
 * @Annotation
 *
 * @deprecated since 1.7, to be removed in 2.0. Use {@link BasePasswordRequirements} instead.
 */
class PasswordStrength extends BasePasswordStrength
{
    public function validatedBy()
    {
        return 'rollerworks_password_strength';
    }
}
