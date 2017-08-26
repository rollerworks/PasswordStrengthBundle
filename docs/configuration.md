Bundle configuration reference
==============================

## Blacklist

The `Rollerworks\Component\PasswordStrength\Validator\Constraints\Blacklist` constraint requires
you configure a blacklist provider. Otherwise any password will be considered valid.

See also https://github.com/rollerworks/PasswordStrengthValidator/blob/master/docs/blacklist.md
for a complete manual on using this constraint.

**New since 2.0:**

> Since 2.0 the `Blacklist` constraint allows to use a different provider then the default.
> Use the `provider` option of the constraint to search in a different provider.
>
> `new Blacklist(['provider' => 'my_customer_provider.name' ])`
>
> Note that only providers registered in the `blacklist.providers` configuration
> can be used.

First you need to set a default provider.

**Note:** Some providers require additional configuring, like database credentials.

> The configuration file is usually located at `app/config/config.yml`
>
> When using Symfony Flex the configuration file may be located elsewhere,
> and could be generated for you (eg. `config/packages/rollerworks_password.yml`).

First you need to configure a default blacklist provider. 
Add the following to your config file:

```yaml
rollerworks_password_strength:
    blacklist:
        # Replace rollerworks_password_strength.blacklist.provider.noop with the service-id of the provider you want to use
        default_provider: rollerworks_password_strength.blacklist.provider.noop
```

The `rollerworks_password_strength.blacklist.provider.noop` is a no-op provider. 
It's main purpose is to ensures the application doesn't break, but you can also use
this to disable password blacklist listing without having to update your code.

### Configuring providers

The PasswordStrength component comes already pre-bundled with support for, in-memory, 
SQLite3, PDO, and a ChainProvider to search in multiple providers.

**Caution:** 

* The `blacklist.default_provider` option accepts any service-id.
* The `blacklist.providers` option is a fixed config-structure of providers.

The `blacklist.providers` option is used to compose a list of loadable provider
services, only configured providers in the list can be used by the `Blacklist` constraint,
and for maintenance commands. _It's not possible to add custom providers (yet)._

<!-- Support for custom providers is planned. -->

This bundle provides an integration for all the pre-bundled provider of the component.
You can choose from:

* rollerworks_password_strength.blacklist.provider.noop: Default implementation, always returns "not blacklisted".
* [rollerworks_password_strength.blacklist.provider.array](#array): In-memory-array blacklist, not recommended for big lists.
* [rollerworks_password_strength.blacklist.provider.sqlite](#sqlite): SQLite3 database file.
* [rollerworks_password_strength.blacklist.provider.chain](#chain): Allows using multiple blacklist providers.

### Array

Update your configuration as follow:

```yaml
rollerworks_password_strength:
    blacklist:
        default_provider: rollerworks_password_strength.blacklist.provider.array
        providers:
            # The 'array' contains a list with all the blacklisted words
            array: [blacklisted-word-1, blacklisted-word-2]
```

### Sqlite

Update your configuration as follow:

```yaml
rollerworks_password_strength:
    blacklist:
        default_provider: rollerworks_password_strength.blacklist.provider.sqlite
        providers:
            sqlite:
                # Make sure the location is outside the cache dir
                dsn: "file:%kernel.root_dir%/Resources/password_blacklist.db"
```

### Chain

The chain provider works by searching in the registered providers,
you can also add service-id of your custom providers.

Update your configuration as follow:

```yaml
rollerworks_password_strength:
    blacklist:
        default_provider: rollerworks_password_strength.blacklist.provider.sqlite
        providers:
            chain:
                lazy: true # Use the LazyChainLoader for better performance (doesn't allow updating at runtime)
                providers:
                    # Add a list of provider service-ids to search in
                    - rollerworks_password_strength.blacklist.provider.array
                    - rollerworks_password_strength.blacklist.provider.sqlite
```

**Note:** The `lazy` option uses `LazyChainLoader` for better performance,
but unlike the "old" `ChainLoader` the loader doesn't allow adding extra providers
at runtime, all providers you want to use _must_ be in the list.

### Custom blacklist provider

To use a custom blacklist provider, first register it in the service container.

Depending your usage, add it to the `providers.chain.providers` list or set the
`default_provider` to the service id.

**Note:** The blacklist provider must implement the
`Rollerworks\Component\PasswordStrength\Blacklist\BlacklistProviderInterface`.

## Commands

Commands for managing the blacklist are automatically registered
when the Symfony Console component is installed.

You can use the `--provider` option to specify a loader to manage.
_This doesn't support custom loaders yet._

See also: https://github.com/rollerworks/PasswordStrengthValidator/blob/master/docs/blacklist.md#commands

