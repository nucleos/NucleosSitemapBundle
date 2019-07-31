<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Tests\DependencyInjection;

use Core23\SitemapBundle\Action\SitemapXMLAction;
use Core23\SitemapBundle\Definition\DefintionManager;
use Core23\SitemapBundle\Definition\DefintionManagerInterface;
use Core23\SitemapBundle\DependencyInjection\Core23SitemapExtension;
use Core23\SitemapBundle\Generator\SitemapGenerator;
use Core23\SitemapBundle\Generator\SitemapGeneratorInterface;
use Core23\SitemapBundle\Sitemap\SitemapServiceManager;
use Core23\SitemapBundle\Sitemap\SitemapServiceManagerInterface;
use Core23\SitemapBundle\Sitemap\StaticSitemapService;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

final class Core23SitemapExtensionTest extends AbstractExtensionTestCase
{
    public function testLoadDefault(): void
    {
        $this->load();

        $this->assertActions();
        $this->assertServices();
        $this->assertSitemap();

        $this->assertContainerBuilderHasParameter('core23_sitemap.static_urls', []);
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

        $this->assertContainerBuilderHasParameter('core23_sitemap.static_urls', [
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
            new Core23SitemapExtension(),
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
