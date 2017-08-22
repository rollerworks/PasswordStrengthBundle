UPGRADE FROM 1.x to 2.0
=======================

Most of this bundle's content has been moved to a separate library
at https://github.com/rollerworks/PasswordStrengthValidator.

You will need to update your class imports to point to the component's
namespace.

All deprecated code has been removed. And support for Symfony 2 and anything
lower then PHP 5.6 was dropped. Official support for HHVM is also dropped.

You need at least Symfony 3.3 and PHP 5.6 (or PHP 7.0).

Constraints
-----------

The constraints have been moved to a separate library.
Update your imports to point to the new namespace.

Before:

```php
use Rollerworks\Bundle\PasswordStrengthBundle\Validator\Constraints as RollerworksPassword;
```

After:

```php
use Rollerworks\Component\PasswordStrength\Validator\Constraints as RollerworksPassword;
```

ChainLoader
-----------

A new `LazyChainLoader` has been added in the library, it is are recommended
to use this loader instead of the old `ChainLoader`.

To enable this loader update your configuration as follow.

Before:

```yaml
rollerworks_password_strength:
    blacklist:
        default_provider: rollerworks_password_strength.blacklist.provider.sqlite
        providers:
            chain:
                providers:
                    - rollerworks_password_strength.blacklist.provider.array
                    - rollerworks_password_strength.blacklist.provider.sqlite
```

After:

```yaml
rollerworks_password_strength:
    blacklist:
        default_provider: rollerworks_password_strength.blacklist.provider.sqlite
        providers:
            chain:
                lazy: true
                providers:
                    - rollerworks_password_strength.blacklist.provider.array
                    - rollerworks_password_strength.blacklist.provider.sqlite
```
