UPGRADE FROM 1.6 to 1.7
=======================

**Note:** 1.7 is the last minor version of the 1.x branch, no new features will
be introduced in this branch. 2.x is compatible with Symfony 3.3 and up.

Support for Symfony 2.3 and PHP 5.3 has been removed, 1.7 requires
at least Symfony 2.8 and PHP 5.6.

A big number of classes have been moved to a separate library
located at https://github.com/rollerworks/PasswordStrengthValidator

Classes in the bundle were kept for compatibility, and will be removed in 2.0.

Blacklist
---------

The Blacklist providers in the bundle have been deprecated and 
will be removed in 2.0. Use the blacklist providers from the 
PasswordStrengthValidator component instead.

Constraints
-----------

The validator constraints in the bundle have been deprecated and 
will be removed in 2.0. Use the validator constraints from the 
PasswordStrengthValidator component instead.

Update your imports to point to the new namespace.

Before:

```php
use Rollerworks\Bundle\PasswordStrengthBundle\Validator\Constraints as RollerworksPassword;
```

After:

```php
use Rollerworks\Component\PasswordStrength\Validator\Constraints as RollerworksPassword;
```
