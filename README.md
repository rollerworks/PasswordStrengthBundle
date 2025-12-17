RollerworksPasswordStrengthBundle
=================================

This Symfony-bundle integrates the Rollerworks [PasswordStrengthValidator][component] into your Symfony application.

_The PasswordStrengthValidator provides various password strength validators for the Symfony Validator._

> This bundle provides the same level of functionality as the
> [PasswordStrengthBundle](https://github.com/jbafford/PasswordStrengthBundle) created by John Bafford.
> And is considered a replacement of the original bundle.

## Installation

To install this package, add `rollerworks/password-strength-bundle` to your composer.json:

```bash
$ php composer.phar require rollerworks/password-strength-bundle
```

Now, [Composer][composer] will automatically download all required files,
and install them for you.

[Symfony Flex][flex]is assumed to enable the Bundle and add required configuration. 
https://symfony.com/doc/current/bundles.html

Otherwise enable the `Rollerworks\Bundle\PasswordStrengthBundle\RollerworksPasswordStrengthBundle`.

## Requirements

You need at least PHP PHP 7.4 and Symfony 7.4, mbstring is recommended but not required.

## Basic Usage

Documentation for the various constraints can be found in the [PasswordStrengthValidator][component] package.

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
please read the [Contributing Guidelines][contributing]. If you're submitting
a pull request, please follow the guidelines in the [Submitting a Patch][patches] section.

[component]: https://github.com/rollerworks/PasswordStrengthValidator
[composer]: https://getcomposer.org/doc/00-intro.md
[flex]: https://symfony.com/doc/current/setup/flex.html
[contributing]: https://contributing.rollerscapes.net/
[patches]: https://contributing.rollerscapes.net/latest/patches.html
