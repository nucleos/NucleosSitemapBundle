<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Tests;

use Core23\SitemapBundle\Core23SitemapBundle;
use Core23\SitemapBundle\DependencyInjection\Compiler\SitemapCompilerPass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Core23SitemapBundleTest extends TestCase
{
    public function testItIsInstantiable(): void
    {
        $bundle = new Core23SitemapBundle();

        $this->assertInstanceOf(Core23SitemapBundle::class, $bundle);
    }

    public function testBuild(): void
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);

        $containerBuilder->expects($this->once())->method('addCompilerPass')
            ->with($this->isInstanceOf(SitemapCompilerPass::class))
        ;

        $bundle = new Core23SitemapBundle();
        $bundle->build($containerBuilder);
    }
}
