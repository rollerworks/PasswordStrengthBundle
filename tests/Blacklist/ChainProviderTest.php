<?php

/*
 * This file is part of the RollerworksPasswordStrengthBundle package.
 *
 * (c) Sebastiaan Stok <s.stok@rollerscapes.net>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Rollerworks\Bundle\PasswordStrengthBundle\tests\Blacklist;

use Rollerworks\Bundle\PasswordStrengthBundle\Blacklist\ArrayProvider;
use Rollerworks\Bundle\PasswordStrengthBundle\Blacklist\ChainProvider;

class ChainProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testBlackList()
    {
        $provider = new ChainProvider();
        $provider->addProvider(new ArrayProvider(['test', 'foobar', 0]));
        $provider->addProvider(new ArrayProvider(['weak', 'god']));

        $this->assertTrue($provider->isBlacklisted('test'));
        $this->assertTrue($provider->isBlacklisted('foobar'));
        $this->assertTrue($provider->isBlacklisted(0));

        $this->assertTrue($provider->isBlacklisted('weak'));
        $this->assertTrue($provider->isBlacklisted('god'));

        $this->assertFalse($provider->isBlacklisted('tests'));
        $this->assertFalse($provider->isBlacklisted(null));
        $this->assertFalse($provider->isBlacklisted(false));
    }

    public function testProvidersByConstruct()
    {
        $provider1 = new ArrayProvider(['test', 'foobar', 0]);
        $provider2 = new ArrayProvider(['weak', 'god']);

        $provider = new ChainProvider([$provider1, $provider2]);

        $this->assertEquals([$provider1, $provider2], $provider->getProviders());
    }

    public function testGetProviders()
    {
        $provider = new ChainProvider();

        $provider1 = new ArrayProvider(['test', 'foobar', 0]);
        $provider2 = new ArrayProvider(['weak', 'god']);

        $provider->addProvider($provider1);
        $provider->addProvider($provider2);

        $this->assertEquals([$provider1, $provider2], $provider->getProviders());
    }

    public function testNoAssignSelf()
    {
        $provider = new ChainProvider();

        $this->setExpectedException('\RuntimeException', 'Unable to add ChainProvider to itself.');
        $provider->addProvider($provider);
    }
}
