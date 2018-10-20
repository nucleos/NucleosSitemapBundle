<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Model;

use Core23\SitemapBundle\Loader\SitemapLoaderInterface;

final class SitemapManager implements SitemapManagerInterface
{
    /**
     * Collection of available sitemaps.
     *
     * @var SitemapDefinitionInterface[]
     */
    private $sitemaps;

    /**
     * @var SitemapLoaderInterface
     */
    private $serviceLoader;

    /**
     * @param SitemapLoaderInterface $serviceLoader
     */
    public function __construct(SitemapLoaderInterface $serviceLoader)
    {
        $this->sitemaps      = [];
        $this->serviceLoader = $serviceLoader;
    }

    /**
     * {@inheritdoc}
     */
    public function addDefinition(string $id, array $configuration = []): SitemapManagerInterface
    {
        $sitemap = $this->serviceLoader->load($configuration);

        $this->add($id, $sitemap);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function add(string $code, SitemapDefinitionInterface $sitemap): SitemapManagerInterface
    {
        $this->sitemaps[$code] = $sitemap;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $code): ?SitemapDefinitionInterface
    {
        if (!isset($this->sitemaps[$code])) {
            return null;
        }

        return $this->sitemaps[$code];
    }

    /**
     * {@inheritdoc}
     */
    public function setAll(array $sitemaps = []): SitemapManagerInterface
    {
        $this->sitemaps = $sitemaps;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAll(): array
    {
        return $this->sitemaps;
    }
}
