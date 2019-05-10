<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Tests\DependencyInjection\Compiler;

use Core23\SitemapBundle\Definition\DefintionManagerInterface;
use Core23\SitemapBundle\DependencyInjection\Compiler\SitemapCompilerPass;
use Core23\SitemapBundle\Sitemap\SitemapServiceManagerInterface;
use Core23\SitemapBundle\Sitemap\StaticSitemapService;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class SitemapCompilerPassTest extends TestCase
{
    private $serviceManager;

    private $definitionManager;

    /**
     * @var ContainerBuilder
     */
    private $container;

    protected function setUp()
    {
        $this->serviceManager = $this->prophesize(Definition::class);
        $this->serviceManager->hasTag('core23.sitemap')
            ->willReturn(false)
        ;
        $this->definitionManager = $this->prophesize(Definition::class);
        $this->definitionManager->hasTag('core23.sitemap')
            ->willReturn(false)
        ;

        $this->container = new ContainerBuilder();
        $this->container->setDefinition(SitemapServiceManagerInterface::class, $this->serviceManager->reveal());
        $this->container->setDefinition(DefintionManagerInterface::class, $this->definitionManager->reveal());
    }

    public function testProcess(): void
    {
        $this->serviceManager->addMethodCall('addSitemap', Argument::that(function ($args) {
            return 'acme.sitemap' === $args[0] && $args[1] instanceof Reference;
        }))
        ->shouldBeCalled()
        ;

        $this->definitionManager->addMethodCall('addDefinition', [
            'acme.sitemap',
        ])
        ->shouldBeCalled()
        ;

        $sitemapDefinition = new Definition();
        $sitemapDefinition->addTag('core23.sitemap');

        $this->container->setParameter('core23_sitemap.static_urls', []);
        $this->container->setDefinition('acme.sitemap', $sitemapDefinition);

        $compiler = new SitemapCompilerPass();
        $compiler->process($this->container);

        static::assertTrue($sitemapDefinition->isPublic());
    }

    public function testProcessWithNoServices(): void
    {
        $this->container->setParameter('core23_sitemap.static_urls', []);

        $compiler = new SitemapCompilerPass();
        $compiler->process($this->container);

        static::assertTrue(true);
    }

    public function testProcessWithStaticUrls(): void
    {
        $this->definitionManager->addMethodCall('addDefinition', [
            StaticSitemapService::class,
            [
                [
                    'url'        => 'http://example.com',
                    'priority'   => 100,
                    'changefreq' => 'daily',
                ],
            ],
        ])
            ->shouldBeCalled()
        ;

        $this->container->setParameter('core23_sitemap.static_urls', [
            'static' => [
                [
                    'url'        => 'http://example.com',
                    'priority'   => 100,
                    'changefreq' => 'daily',
                ],
            ],
        ]);

        $compiler = new SitemapCompilerPass();
        $compiler->process($this->container);
    }

    public function testProcessWithEmptyGroups(): void
    {
        $this->container->setParameter('core23_sitemap.static_urls', []);

        $compiler = new SitemapCompilerPass();
        $compiler->process($this->container);

        $this->serviceManager->addMethodCall(Argument::any())->shouldNotBeCalled();
        $this->definitionManager->addMethodCall(Argument::any())->shouldNotBeCalled();
    }
}
