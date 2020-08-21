<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SitemapBundle\Tests\DependencyInjection\Compiler;

use Nucleos\SitemapBundle\Definition\DefintionManagerInterface;
use Nucleos\SitemapBundle\DependencyInjection\Compiler\SitemapCompilerPass;
use Nucleos\SitemapBundle\Sitemap\SitemapServiceManagerInterface;
use Nucleos\SitemapBundle\Sitemap\StaticSitemapService;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

final class SitemapCompilerPassTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @var ObjectProphecy<Definition>
     */
    private $serviceManager;

    /**
     * @var ObjectProphecy<Definition>
     */
    private $definitionManager;

    /**
     * @var ContainerBuilder
     */
    private $container;

    protected function setUp(): void
    {
        $this->serviceManager = $this->prophesize(Definition::class);
        $this->serviceManager->hasTag('nucleos.sitemap')
            ->willReturn(false)
        ;
        $this->definitionManager = $this->prophesize(Definition::class);
        $this->definitionManager->hasTag('nucleos.sitemap')
            ->willReturn(false)
        ;

        $this->container = new ContainerBuilder();
        $this->container->setDefinition(SitemapServiceManagerInterface::class, $this->serviceManager->reveal());
        $this->container->setDefinition(DefintionManagerInterface::class, $this->definitionManager->reveal());
    }

    public function testProcess(): void
    {
        $this->serviceManager->addMethodCall('addSitemap', Argument::that(static function (array $args): bool {
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
        $sitemapDefinition->addTag('nucleos.sitemap');

        $this->container->setParameter('nucleos_sitemap.static_urls', []);
        $this->container->setDefinition('acme.sitemap', $sitemapDefinition);

        $compiler = new SitemapCompilerPass();
        $compiler->process($this->container);

        static::assertTrue($sitemapDefinition->isPublic());

        $this->definitionManager->addMethodCall('addDefintion', Argument::any())->shouldNotHaveBeenCalled();
    }

    public function testProcessWithNoServices(): void
    {
        $this->container->setParameter('nucleos_sitemap.static_urls', []);

        $compiler = new SitemapCompilerPass();
        $compiler->process($this->container);

        static::assertSame([], $this->container->getParameter('nucleos_sitemap.static_urls'));

        $this->definitionManager->addMethodCall('addDefintion', Argument::any())->shouldNotHaveBeenCalled();
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

        $this->container->setParameter('nucleos_sitemap.static_urls', [
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
        $this->container->setParameter('nucleos_sitemap.static_urls', []);

        $compiler = new SitemapCompilerPass();
        $compiler->process($this->container);

        $this->serviceManager->addMethodCall(Argument::any())->shouldNotBeCalled();
        $this->definitionManager->addMethodCall(Argument::any())->shouldNotBeCalled();
    }
}
