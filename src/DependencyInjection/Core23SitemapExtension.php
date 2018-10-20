<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\DependencyInjection;

use Core23\SitemapBundle\Generator\SitemapGenerator;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class Core23SitemapExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('action.xml');
        $loader->load('services.xml');
        $loader->load('sitemap.xml');

        $this->configureCache($container, $config);
        $this->configureStaticUrls($container, $config);
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $config
     */
    private function configureCache(ContainerBuilder $container, array $config): void
    {
        if (null === $config['cache']['service']) {
            return;
        }

        $container->getDefinition(SitemapGenerator::class)
            ->replaceArgument(2, new Reference($config['cache']['service']));
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $config
     */
    private function configureStaticUrls(ContainerBuilder $container, array $config): void
    {
        $container->setParameter('core23_sitemap.static_urls', $config['static']);
    }
}
