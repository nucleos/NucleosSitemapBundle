<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Sitemap;

use Core23\SitemapBundle\Definition\SitemapDefinitionInterface;
use Core23\SitemapBundle\Exception\SitemapNotFoundException;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class SitemapServiceManager implements SitemapServiceManagerInterface
{
    /**
     * @var SitemapServiceInterface[]
     */
    private $services;

    /**
     * @param SitemapServiceInterface[] $services
     */
    public function __construct(array $services = [])
    {
        $this->services = [];

        foreach ($services as $id => $service) {
            $this->addSitemap($id, $service);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get(SitemapDefinitionInterface $definition): ?SitemapServiceInterface
    {
        $service = $this->getService($definition->getType());

        $optionsResolver = new OptionsResolver();
        $this->configureSettings($optionsResolver, $service);

        $settings = $optionsResolver->resolve($definition->getSettings());
        $definition->setSettings($settings);

        return $service;
    }

    /**
     * {@inheritdoc}
     */
    public function addSitemap(string $id, SitemapServiceInterface $service): void
    {
        $this->services[$id] = $service;
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    private function has(string $id): bool
    {
        return isset($this->services[$id]) ? true : false;
    }

    /**
     * @param string $id
     *
     * @return SitemapServiceInterface
     */
    private function getService(string $id): SitemapServiceInterface
    {
        if (!$this->has($id)) {
            throw new SitemapNotFoundException(sprintf('The sitemap service `%s` does not exist', $id));
        }

        return $this->services[$id];
    }

    /**
     * @param OptionsResolver         $resolver
     * @param SitemapServiceInterface $sitemap
     */
    private function configureSettings(OptionsResolver $resolver, SitemapServiceInterface $sitemap): void
    {
        $resolver
            ->setDefaults([
                'use_cache'        => true,
                'extra_cache_keys' => [],
                'ttl'              => 86400,
            ])
            ->setAllowedTypes('use_cache', 'bool')
            ->setAllowedTypes('extra_cache_keys', 'array')
            ->setAllowedTypes('ttl', 'int')
        ;

        $sitemap->configureSettings($resolver);
    }
}
