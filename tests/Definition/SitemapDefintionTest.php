<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Tests\Definition;

use Core23\SitemapBundle\Definition\SitemapDefinition;
use Core23\SitemapBundle\Definition\SitemapDefinitionInterface;
use PHPUnit\Framework\TestCase;

class SitemapDefintionTest extends TestCase
{
    public function testItIsInstantiable(): void
    {
        $definition = new SitemapDefinition('acme.sitemap');

        $this->assertInstanceOf(SitemapDefinitionInterface::class, $definition);
        $this->assertSame('acme.sitemap', $definition->getType());
        $this->assertSame('acme.sitemap', $definition->toString());
        $this->assertSame('acme.sitemap', $definition->__toString());
        $this->assertSame([], $definition->getSettings());
    }

    public function testSetting(): void
    {
        $definition = new SitemapDefinition('acme.sitemap');
        $definition->setSettings([
            'foo'=> 'bar',
        ]);

        $this->assertSame('bar', $definition->getSetting('foo'));
    }

    public function testSettingWithDefault(): void
    {
        $definition = new SitemapDefinition('acme.sitemap');

        $this->assertSame('baz', $definition->getSetting('foo', 'baz'));
    }

    public function testTtl(): void
    {
        $definition = new SitemapDefinition('acme.sitemap', [
            'use_cache' => true,
            'ttl'       => 90,
        ]);

        $this->assertSame(90, $definition->getTtl());
    }

    public function testTtlWithoutCache(): void
    {
        $definition = new SitemapDefinition('acme.sitemap', [
            'use_cache' => false,
        ]);

        $this->assertSame(0, $definition->getTtl());
    }

    public function testTtlDefault(): void
    {
        $definition = new SitemapDefinition('acme.sitemap');

        $this->assertSame(0, $definition->getTtl());
    }
}
