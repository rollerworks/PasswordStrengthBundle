Password blacklisting
=====================

The `\Rollerworks\Component\PasswordStrength\Validator\Constraints\Blacklist` constraint requires
you configure a blacklist provider. Otherwise any password will be considered valid.

## Configuration

First you need to configure a blacklist provider.

**Tip.** You can use the ChainProvider to yse multiple providers at once.

The `default_provider` option contains the service-name of the blacklist provider.

This bundle provides an integration for all the pre-bundled provider of the component.
You can choose from:

* rollerworks_password_strength.blacklist.provider.noop: Default implementation, always returns "not blacklisted".
* [rollerworks_password_strength.blacklist.provider.array](#array): In-memory-array blacklist, not recommended for big lists.
* [rollerworks_password_strength.blacklist.provider.sqlite](#sqlite): SQLite3 database file, updatable using the rollerworks-password:blacklist:update console command.
* [rollerworks_password_strength.blacklist.provider.chain](#chain): Allows using multiple blacklist providers.

Add the following to your config file:

```yaml
# app/config/config.yml

rollerworks_password_strength:
    blacklist:
        # Replace rollerworks_password_strength.blacklist.provider.noop with the service you want to use
        default_provider: rollerworks_password_strength.blacklist.provider.noop
```

### Array

Add the following to your config file:

```yaml
# app/config/config.yml

rollerworks_password_strength:
    blacklist:
        default_provider: rollerworks_password_strength.blacklist.provider.array
        providers:
            # The 'array' contains a list with all the blacklisted words
            array: [blacklisted-word-1, blacklisted-word-2]
```

### Sqlite

Add the following to your config file:

```yaml
# app/config/config.yml

rollerworks_password_strength:
    blacklist:
        default_provider: rollerworks_password_strength.blacklist.provider.sqlite
        providers:
            sqlite:
                # Make sure the location is outside the cache dir
                dsn: "file:%kernel.root_dir%/Resources/password_blacklist.db"
```

### Chain

The chain provider works by searching in the registered providers.

Add the following to your config file:

```yaml
# app/config/config.yml

rollerworks_password_strength:
    blacklist:
        default_provider: rollerworks_password_strength.blacklist.provider.sqlite
        providers:
            chain:
                lazy: true # Use the LazyChainLoader for better performance (doesn't allow updating at runtime)
                providers:
                    # Add a list of services to search in
                    - rollerworks_password_strength.blacklist.provider.array
                    - rollerworks_password_strength.blacklist.provider.sqlite
```

### Custom blacklist provider

To use a custom blacklist provider first register it in the service container,
add it to the `providers` list and set the `default_provider` to the service id,
or add it to the `providers.chain.providers` list.

**Note:** The blacklist provider must implement the
`Rollerworks\Component\PasswordStrength\Blacklist\BlacklistProviderInterface`.

## Commands

Commands for managing the blacklist are automatically registered
when the Symfony Console component is installed.
