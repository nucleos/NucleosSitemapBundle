<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SitemapBundle\Tests;

use Nucleos\SitemapBundle\DependencyInjection\Compiler\SitemapCompilerPass;
use Nucleos\SitemapBundle\DependencyInjection\NucleosSitemapExtension;
use Nucleos\SitemapBundle\NucleosSitemapBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class NucleosSitemapBundleTest extends TestCase
{
    public function testGetContainerExtension(): void
    {
        $bundle = new NucleosSitemapBundle();

        static::assertInstanceOf(NucleosSitemapExtension::class, $bundle->getContainerExtension());
    }

    public function testBuild(): void
    {
        $containerBuilder = $this->createMock(ContainerBuilder::class);

        $containerBuilder->expects(static::once())->method('addCompilerPass')
            ->with(static::isInstanceOf(SitemapCompilerPass::class))
        ;

        $bundle = new NucleosSitemapBundle();
        $bundle->build($containerBuilder);
    }
}
