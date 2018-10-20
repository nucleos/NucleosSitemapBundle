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

        foreach ($services as $service) {
            $this->addSitemap($service);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get(SitemapDefinitionInterface $sitemap): ?SitemapServiceInterface
    {
        $service = $this->getService($sitemap->getType());

        $optionsResolver = new OptionsResolver();
        $this->configureSettings($optionsResolver, $service);

        $settings = $optionsResolver->resolve($sitemap->getSettings());
        $sitemap->setSettings($settings);

        return $service;
    }

    /**
     * {@inheritdoc}
     */
    public function has($id): bool
    {
        return isset($this->services[$id]) ? true : false;
    }

    /**
     * {@inheritdoc}
     */
    public function getService(string $id): SitemapServiceInterface
    {
        if (!$this->has($id)) {
            throw new SitemapNotFoundException(sprintf('The sitemap service `%s` does not exist', $id));
        }

        return $this->services[$id];
    }

    /**
     * {@inheritdoc}
     */
    public function getServices(): array
    {
        return $this->services;
    }

    /**
     * {@inheritdoc}
     */
    public function addSitemap(SitemapServiceInterface $service): void
    {
        $this->services[$service->getName()] = $service;
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
                'ttl'              => 0,
            ])
            ->setAllowedTypes('use_cache', 'bool')
            ->setAllowedTypes('extra_cache_keys', 'array')
            ->setAllowedTypes('ttl', 'int')
        ;

        $sitemap->configureSettings($resolver);
    }
}
