RollerworksPasswordStrengthBundle
=================================

This Symfony-bundle integrates the Rollerworks [PasswordStrengthValidator][1] into your Symfony application.

_The PasswordStrengthValidator provides various password strength validators for the Symfony Validator._

> This bundle provides the same level of functionality as the
> [PasswordStrengthBundle](https://github.com/jbafford/PasswordStrengthBundle) created by John Bafford.
> And is considered a replacement of the original bundle.

## Installation

To install this package, add `rollerworks/password-strength-bundle` to your composer.json:

```bash
$ php composer.phar require rollerworks/password-strength-bundle
```

Now, [Composer][2] will automatically download all required files, and install them
for you.

### Step2: Enable the bundle

**Note:** This step is **not** required for Symfony Flex.

Enable the bundle in the kernel:

```php
<?php

// in AppKernel::registerBundles()
$bundles = [
    // ...
    new Rollerworks\Bundle\PasswordStrengthBundle\RollerworksPasswordStrengthBundle(),
    // ...
];
```

## Requirements

You need at least PHP 5.6 or PHP 7.0, mbstring is recommended but not required.
For the provided blacklist providers you may need SQLite3 or a PDO compatible driver.

Congratulations! You're ready!

## Basic Usage

Documentation for the various constraints can be found in the [PasswordStrengthValidator][1] package.
See the [bundle reference configuration](docs/configuration.md) to configure usage with this bundle.

## Versioning

For transparency and insight into the release cycle, and for striving
to maintain backward compatibility, this package is maintained under
the Semantic Versioning guidelines as much as possible.

Releases will be numbered with the following format:

`<major>.<minor>.<patch>`

And constructed with the following guidelines:

* Breaking backward compatibility bumps the major (and resets the minor and patch)
* New additions without breaking backward compatibility bumps the minor (and resets the patch)
* Bug fixes and misc changes bumps the patch

For more information on SemVer, please visit <http://semver.org/>.

## License

This library is released under the [MIT license](LICENSE).

## Contributing

This is an open source project. If you'd like to contribute,
please read the [Contributing Guidelines][3]. If you're submitting
a pull request, please follow the guidelines in the [Submitting a Patch][4] section.

[1]: https://github.com/rollerworks/PasswordStrengthValidator
[2]: https://getcomposer.org/doc/00-intro.md
[3]: https://github.com/rollerworks/contributing
[4]: https://contributing.readthedocs.org/en/latest/code/patches.html
