<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SitemapBundle\DependencyInjection;

use Nucleos\SitemapBundle\Generator\SitemapGenerator;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class NucleosSitemapExtension extends Extension
{
    /**
     * @param array<mixed> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\PhpFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('action.php');
        $loader->load('services.php');
        $loader->load('sitemap.php');

        $this->configureCache($container, $config);
        $this->configureStaticUrls($container, $config);
    }

    /**
     * @param array<mixed> $config
     */
    private function configureCache(ContainerBuilder $container, array $config): void
    {
        if (null === $config['cache']['service']) {
            return;
        }

        $container->getDefinition(SitemapGenerator::class)
            ->replaceArgument(2, new Reference($config['cache']['service']))
        ;
    }

    /**
     * @param array<mixed> $config
     */
    private function configureStaticUrls(ContainerBuilder $container, array $config): void
    {
        $container->setParameter('nucleos_sitemap.static_urls', $config['static']);
    }
}
