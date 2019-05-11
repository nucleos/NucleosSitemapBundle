<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Tests\Sitemap;

use Core23\SitemapBundle\Definition\SitemapDefinition;
use Core23\SitemapBundle\Definition\SitemapDefinitionInterface;
use Core23\SitemapBundle\Exception\SitemapNotFoundException;
use Core23\SitemapBundle\Sitemap\SitemapServiceInterface;
use Core23\SitemapBundle\Sitemap\SitemapServiceManager;
use Core23\SitemapBundle\Sitemap\SitemapServiceManagerInterface;
use Core23\SitemapBundle\Tests\Fixtures\SitemapService;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

class SitemapServiceManagerTest extends TestCase
{
    public function testItIsInstantiable(): void
    {
        $manager = new SitemapServiceManager();

        static::assertInstanceOf(SitemapServiceManagerInterface::class, $manager);
    }

    public function testCreationWithInvalidServices(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The "stdClass" service is not a valid SitemapServiceInterface');

        new SitemapServiceManager([
            'invalid' => new stdClass(),
        ]);
    }

    public function testGet(): void
    {
        $definition = new SitemapDefinition('my-type', []);

        $service = new SitemapService();

        $manager = new SitemapServiceManager([
            'my-type' => $service,
        ]);
        $result =  $manager->get($definition);

        static::assertInstanceOf(SitemapServiceInterface::class, $result);
        static::assertSame([
            'custom'           => 'foo',
            'use_cache'        => true,
            'extra_cache_keys' => [],
            'ttl'              => 86400,
        ], $definition->getSettings());
    }

    public function testGetWithOverride(): void
    {
        $definition = new SitemapDefinition('my-type', [
            'custom'           => 'bar',
            'use_cache'        => false,
            'extra_cache_keys' => ['my-key'],
            'ttl'              => 0,
        ]);

        $service = new SitemapService();

        $manager = new SitemapServiceManager([
            'my-type' => $service,
        ]);
        $result =  $manager->get($definition);

        static::assertInstanceOf(SitemapServiceInterface::class, $result);
        static::assertSame([
            'use_cache'        => false,
            'extra_cache_keys' => ['my-key'],
            'ttl'              => 0,
            'custom'           => 'bar',
        ], $definition->getSettings());
    }

    public function testGetWithInvalidOverride(): void
    {
        $this->expectException(UndefinedOptionsException::class);
        $this->expectExceptionMessage('The option "invalid" does not exist. Defined options are: "custom", "extra_cache_keys", "ttl", "use_cache"');

        $definition = new SitemapDefinition('my-type', [
            'invalid' => 'value',
        ]);

        $service = new SitemapService();

        $manager = new SitemapServiceManager([
            'my-type' => $service,
        ]);
        $manager->get($definition);
    }

    public function testGetWithInvalidDefinition(): void
    {
        $this->expectException(SitemapNotFoundException::class);
        $this->expectExceptionMessage('The sitemap service "my-type" does not exist');

        $definition = $this->prophesize(SitemapDefinitionInterface::class);
        $definition->getType()
            ->willReturn('my-type')
        ;
        $definition->getSettings()
            ->willReturn([])
        ;

        $manager = new SitemapServiceManager();
        $manager->get($definition->reveal());
    }

    public function testAddSitemap(): void
    {
        $service = $this->prophesize(SitemapServiceInterface::class);

        $manager = new SitemapServiceManager();
        $manager->addSitemap('my-type', $service->reveal());

        static::assertTrue(true);
    }
}
