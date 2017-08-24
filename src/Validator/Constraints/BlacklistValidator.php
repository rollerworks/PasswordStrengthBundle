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

use Rollerworks\Component\PasswordStrength\Validator\Constraints\BlacklistValidator as BaseBlacklistValidator;

/**
 * Password Blacklist Validation.
 *
 * Validates if the password is blacklisted/blocked for usage.
 *
 * @author Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * @deprecated since 1.7, to be removed in 2.0. Use {@link BaseBlacklistValidator} instead.
 */
class BlacklistValidator extends BaseBlacklistValidator
{
}
