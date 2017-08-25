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

use Rollerworks\Component\PasswordStrength\Validator\Constraints\PasswordStrengthValidator as BasePasswordStrengthValidator;

/**
 * Password strength Validation.
 *
 * Validates if the password strength is equal or higher
 * to the required minimum and the password length is equal
 * or longer than the minimum length.
 *
 * The strength is computed from various measures including
 * length and usage of characters.
 *
 * The strengths are marked up as follow.
 *  1: Very Weak
 *  2: Weak
 *  3: Medium
 *  4: Strong
 *  5: Very Strong
 *
 * @author Sebastiaan Stok <s.stok@rollerscapes.net>
 * @author Shouvik Chatterjee <mailme@shouvik.net>
 *
 * @deprecated since 1.7, to be removed in 2.0. Use {@link BasePasswordStrengthValidator} instead.
 */
class PasswordStrengthValidator extends BasePasswordStrengthValidator
{
}
