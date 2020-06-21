<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SitemapBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Nucleos\SitemapBundle\Action\SitemapXMLAction;
use Nucleos\SitemapBundle\Definition\DefintionManager;
use Nucleos\SitemapBundle\Definition\DefintionManagerInterface;
use Nucleos\SitemapBundle\DependencyInjection\NucleosSitemapExtension;
use Nucleos\SitemapBundle\Generator\SitemapGenerator;
use Nucleos\SitemapBundle\Generator\SitemapGeneratorInterface;
use Nucleos\SitemapBundle\Sitemap\SitemapServiceManager;
use Nucleos\SitemapBundle\Sitemap\SitemapServiceManagerInterface;
use Nucleos\SitemapBundle\Sitemap\StaticSitemapService;

final class NucleosSitemapExtensionTest extends AbstractExtensionTestCase
{
    public function testLoadDefault(): void
    {
        $this->load();

        $this->assertActions();
        $this->assertServices();
        $this->assertSitemap();

        $this->assertContainerBuilderHasParameter('nucleos_sitemap.static_urls', []);
    }

    public function testLoadWithCacheService(): void
    {
        $this->load([
            'cache' => [
                'service' => 'acme.foo.service',
            ],
        ]);

        $this->assertContainerBuilderHasServiceDefinitionWithArgument(SitemapGenerator::class, 2);
    }

    public function testLoadWithStaticSitemaps(): void
    {
        $this->load([
            'static' => [
                [
                    'url'        => 'http://example.com',
                    'priority'   => 100,
                    'changefreq' => 'daily',
                ],
            ],
        ]);

        $this->assertContainerBuilderHasParameter('nucleos_sitemap.static_urls', [
            [
                'url'        => 'http://example.com',
                'priority'   => 100,
                'changefreq' => 'daily',
            ],
        ]);
    }

    protected function getContainerExtensions(): array
    {
        return [
            new NucleosSitemapExtension(),
        ];
    }

    private function assertActions(): void
    {
        $this->assertContainerBuilderHasService(SitemapXMLAction::class);
    }

    private function assertServices(): void
    {
        $this->assertContainerBuilderHasAlias(SitemapServiceManagerInterface::class, SitemapServiceManager::class);
        $this->assertContainerBuilderHasAlias(DefintionManagerInterface::class, DefintionManager::class);
        $this->assertContainerBuilderHasAlias(SitemapGeneratorInterface::class, SitemapGenerator::class);

        $this->assertContainerBuilderHasService(SitemapServiceManager::class);
        $this->assertContainerBuilderHasService(DefintionManager::class);
        $this->assertContainerBuilderHasService(SitemapGenerator::class);
    }

    private function assertSitemap(): void
    {
        $this->assertContainerBuilderHasService(StaticSitemapService::class);
    }
}
