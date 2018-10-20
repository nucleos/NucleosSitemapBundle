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

class SitemapManager implements SitemapManagerInterface
{
    /**
     * Collection of available sitemaps.
     *
     * @var SitemapInterface[]
     */
    protected $sitemaps;

    /**
     * @var SitemapLoaderInterface
     */
    protected $serviceLoader;

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
    public function add(string $code, SitemapInterface $sitemap): SitemapManagerInterface
    {
        $this->sitemaps[$code] = $sitemap;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $code): ?SitemapInterface
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
