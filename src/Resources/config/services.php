<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Nucleos\SitemapBundle\Definition\DefintionManager;
use Nucleos\SitemapBundle\Definition\DefintionManagerInterface;
use Nucleos\SitemapBundle\Generator\SitemapGenerator;
use Nucleos\SitemapBundle\Generator\SitemapGeneratorInterface;
use Nucleos\SitemapBundle\Sitemap\SitemapServiceManager;
use Nucleos\SitemapBundle\Sitemap\SitemapServiceManagerInterface;
use Symfony\Component\DependencyInjection\Reference;

return static function (ContainerConfigurator $container): void {
    $container->services()

        ->alias(SitemapServiceManagerInterface::class, SitemapServiceManager::class)
            ->public()

        ->alias(DefintionManagerInterface::class, DefintionManager::class)
            ->public()

        ->alias(SitemapGeneratorInterface::class, SitemapGenerator::class)

        ->set(SitemapServiceManager::class)

        ->set(DefintionManager::class)

        ->set(SitemapGenerator::class)
            ->args([
                new Reference(SitemapServiceManagerInterface::class),
                new Reference(DefintionManagerInterface::class),
                null,
            ])

    ;
};
