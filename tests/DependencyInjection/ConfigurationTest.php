<?php

/*
 * This file is part of the RollerworksPasswordStrengthBundle package.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Rollerworks\Bundle\PasswordStrengthBundle\Tests\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\AbstractConfigurationTestCase;
use Rollerworks\Bundle\PasswordStrengthBundle\DependencyInjection\Configuration;

class ConfigurationTest extends AbstractConfigurationTestCase
{
    protected function getConfiguration()
    {
        return new Configuration();
    }

    public function testNoBlacklistProvidersConfiguredByDefault()
    {
        $this->assertProcessedConfigurationEquals(
            [
                [],
            ],
            [
                'blacklist' => [
                    'default_provider' => 'rollerworks_password_strength.blacklist.provider.noop',
                ],
            ]
        );
    }

    public function testSqlLiteBlacklistProviderIsConfigured()
    {
        $this->assertProcessedConfigurationEquals(
            [
                [
                    'blacklist' => [
                        'providers' => [
                            'sqlite' => ['dsn' => 'sqlite:/path/to/the/db/file'],
                        ],
                    ],
                ],
            ],
            [
                'blacklist' => [
                    'providers' => [
                        'sqlite' => ['dsn' => 'sqlite:/path/to/the/db/file'],
                        'array' => [],
                    ],
                    'default_provider' => 'rollerworks_password_strength.blacklist.provider.noop',
                ],
            ]
        );
    }

    public function testArrayBlacklistProviderIsConfigured()
    {
        $this->assertProcessedConfigurationEquals(
            [
                [
                    'blacklist' => [
                        'providers' => [
                            'array' => ['foo', 'foobar', 'kaboom'],
                        ],
                    ],
                ],
            ],
            [
                'blacklist' => [
                    'providers' => [
                        'array' => ['foo', 'foobar', 'kaboom'],
                    ],
                    'default_provider' => 'rollerworks_password_strength.blacklist.provider.noop',
                ],
            ]
        );
    }

    public function testConfigChain()
    {
        $this->assertProcessedConfigurationEquals(
            [
                [
                    'blacklist' => [
                        'providers' => [
                            'chain' => [
                                'lazy' => false,
                                'providers' => [
                                    'rollerworks_password_strength.blacklist.provider.array',
                                    'rollerworks_password_strength.blacklist.provider.sqlite',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            [
                'blacklist' => [
                    'providers' => [
                        'chain' => [
                            'lazy' => false,
                            'providers' => [
                                'rollerworks_password_strength.blacklist.provider.array',
                                'rollerworks_password_strength.blacklist.provider.sqlite',
                            ],
                        ],
                        'array' => [],
                    ],
                    'default_provider' => 'rollerworks_password_strength.blacklist.provider.noop',
                ],
            ]
        );
    }
}
