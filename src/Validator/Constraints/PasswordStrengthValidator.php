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

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Password strength Validation.
 *
 * Validates if the password strength is equal or higher
 * to the required minimum.
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
 */
class PasswordStrengthValidator extends ConstraintValidator
{
    /**
     * @param string                      $password
     * @param PasswordStrength|Constraint $constraint
     */
    public function validate($password, Constraint $constraint)
    {
        if (null === $password || '' === $password) {
            return;
        }

        if (null !== $password && !is_scalar($password) && !(is_object($password) && method_exists($password, '__toString'))) {
            throw new UnexpectedTypeException($password, 'string');
        }

        $password = (string) $password;

        $passwordStrength = 0;
        $passLength = strlen($password);

        if ($passLength < $constraint->minLength) {
            if ($this->context instanceof ExecutionContextInterface) {
                $this->context->buildViolation($constraint->message)
                    ->setParameters(array('{{ length }}' => $constraint->minLength))
                    ->addViolation();
            } else {
                $this->context->addViolation($constraint->message, array('{{ length }}' => $constraint->minLength));
            }

            return;
        }

        $alpha = $digit = $specialChar = false;

        if ($passLength >= $constraint->minLength) {
            $passwordStrength++;
        }

        if (preg_match('/[a-z]/', $password) && preg_match('/[A-Z]/', $password)) {
            $alpha = true;
            $passwordStrength++;
        }

        if (preg_match('/\d+/', $password)) {
            $digit = true;
            $passwordStrength++;
        }

        if (preg_match('/[^a-zA-Z0-9]/', $password)) {
            $specialChar = true;
            $passwordStrength++;
        }

        if ($passLength > 12) {
            $passwordStrength++;
        }

        // No decrease strength on weak combinations

        // Only digits no alpha or special char
        if ($digit && !$alpha && !$specialChar) {
            $passwordStrength--;
        } elseif ($alpha && !$digit) {
            $passwordStrength--;
        }

        if ($passwordStrength < $constraint->minStrength) {
            if ($this->context instanceof ExecutionContextInterface) {
                $this->context->buildViolation($constraint->message)
                    ->setParameters(array('{{ length }}' => $constraint->minLength))
                    ->addViolation();
            } else {
                $this->context->addViolation($constraint->message, array('{{ length }}' => $constraint->minLength));
            }
        }
    }
}
