<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SitemapBundle\Tests\Sitemap;

use Nucleos\SitemapBundle\Definition\SitemapDefinition;
use Nucleos\SitemapBundle\Definition\SitemapDefinitionInterface;
use Nucleos\SitemapBundle\Exception\SitemapNotFoundException;
use Nucleos\SitemapBundle\Sitemap\SitemapServiceInterface;
use Nucleos\SitemapBundle\Sitemap\SitemapServiceManager;
use Nucleos\SitemapBundle\Tests\Fixtures\SitemapDefinitionStub;
use Nucleos\SitemapBundle\Tests\Fixtures\SitemapService;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use stdClass;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use TypeError;

final class SitemapServiceManagerTest extends TestCase
{
    use ProphecyTrait;

    public function testCreationWithInvalidServices(): void
    {
        $this->expectException(TypeError::class);

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

        $definition = new SitemapDefinitionStub('my-type');

        static::assertSame($service->reveal(), $manager->get($definition));
    }
}
