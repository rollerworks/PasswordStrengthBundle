<?php

/*
 * This file is part of the RollerworksPasswordStrengthBundle package.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Rollerworks\Bundle\PasswordStrengthBundle\tests\Validator;

use Rollerworks\Bundle\PasswordStrengthBundle\Validator\Constraints\PasswordRequirements;
use Rollerworks\Bundle\PasswordStrengthBundle\Validator\Constraints\PasswordRequirementsValidator;
use Symfony\Component\Validator\Tests\Constraints\AbstractConstraintValidatorTest;
use Symfony\Component\Validator\Validation;

class PasswordRequirementsValidatorTest extends AbstractConstraintValidatorTest
{
    protected function getApiVersion()
    {
        return Validation::API_VERSION_2_5;
    }

    protected function createValidator()
    {
        return new PasswordRequirementsValidator();
    }

    public function testNullIsValid()
    {
        $this->validator->validate(null, new PasswordRequirements());

        $this->assertNoViolation();
    }

    public function testEmptyIsValid()
    {
        $this->validator->validate('', new PasswordRequirements());

        $this->assertNoViolation();
    }

    /**
     * @dataProvider provideValidConstraints
     *
     * @param string               $value
     * @param PasswordRequirements $constraint
     */
    public function testValidValueConstraints($value, PasswordRequirements $constraint)
    {
        $this->value = $value;

        $this->validator->validate($value, $constraint);

        $this->assertNoViolation();
    }

    /**
     * @dataProvider provideViolationConstraints
     *
     * @param string               $value
     * @param PasswordRequirements $constraint
     * @param array                $violations
     */
    public function testViolationValueConstraints($value, PasswordRequirements $constraint, array $violations = [])
    {
        $this->value = $value;

        $this->validator->validate($value, $constraint);

        foreach ($violations as $i => $violation) {
            if ($i == 0) {
                $constraintViolationAssertion = $this->buildViolation($violation[0])
                    ->setParameters(isset($violation[1]) ? $violation[1] : [])
                    ->setInvalidValue($value);
            } else {
                $constraintViolationAssertion = $constraintViolationAssertion->buildNextViolation($violation[0])
                    ->setParameters(isset($violation[1]) ? $violation[1] : [])
                    ->setInvalidValue($value);
            }
            if ($i == count($violations) - 1) {
                $constraintViolationAssertion->assertRaised();
            }
        }
    }

    public function provideValidConstraints()
    {
        return [
            ['test', new PasswordRequirements(['minLength' => 3])],
            ['1234567', new PasswordRequirements(['requireLetters' => false])],
            ['1234567', new PasswordRequirements(['requireLetters' => false])],
            ['aBcDez', new PasswordRequirements(['requireCaseDiff' => true])],
            ['abcdef', new PasswordRequirements(['requireNumbers' => false])],
            ['123456', new PasswordRequirements(['requireLetters' => false, 'requireNumbers' => true])],
            ['１２３４５６７８９', new PasswordRequirements(['requireLetters' => false, 'requireNumbers' => true])],
            ['abcd12345', new PasswordRequirements(['requireLetters' => true, 'requireNumbers' => true])],
            ['１２３４abc５６７８９', new PasswordRequirements(['requireLetters' => true, 'requireNumbers' => true])],

            ['®', new PasswordRequirements(['minLength' => 1, 'requireLetters' => false, 'requireSpecialCharacter' => true])],
            ['»', new PasswordRequirements(['minLength' => 1, 'requireLetters' => false, 'requireSpecialCharacter' => true])],
            ['<>', new PasswordRequirements(['minLength' => 1, 'requireLetters' => false, 'requireSpecialCharacter' => true])],
        ];
    }

    public function provideViolationConstraints()
    {
        $constraint = new PasswordRequirements();

        return [
            ['test', new PasswordRequirements(['requireLetters' => true]), [
                [$constraint->tooShortMessage, ['{{length}}' => $constraint->minLength]],
            ]],
            ['123456', new PasswordRequirements(['requireLetters' => true]), [
                [$constraint->missingLettersMessage],
            ]],
            ['abcdez', new PasswordRequirements(['requireCaseDiff' => true]), [
                [$constraint->requireCaseDiffMessage],
            ]],
            ['!@#$%^&*()-', new PasswordRequirements(['requireLetters' => true, 'requireNumbers' => true]), [
                [$constraint->missingLettersMessage],
                [$constraint->missingNumbersMessage],
            ]],
            ['aerfghy', new PasswordRequirements(['requireLetters' => false, 'requireSpecialCharacter' => true]), [
                [$constraint->missingSpecialCharacterMessage],
            ]],
        ];
    }
}
