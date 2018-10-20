<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Model;

interface SitemapManagerInterface
{
    /**
     * @param SitemapDefinitionInterface[] $sitemaps
     *
     * @return self
     */
    public function setAll(array $sitemaps = []): self;

    /**
     * Returns the sitemaps.
     *
     * @return SitemapDefinitionInterface[]
     */
    public function getAll(): array;

    /**
     * Adds a sitemap definition.
     *
     * @param string $id
     * @param array  $configuration
     *
     * @return self
     */
    public function addDefinition(string $id, array $configuration = []): self;

    /**
     * Adds a sitemap.
     *
     * @param string                     $code    Code
     * @param SitemapDefinitionInterface $sitemap sitemap object
     *
     * @return self
     */
    public function add(string $code, SitemapDefinitionInterface $sitemap): self;

    /**
     * Returns the sitemap by code.
     *
     * @param string $code
     *
     * @return SitemapDefinitionInterface|null
     */
    public function get(string $code): ?SitemapDefinitionInterface;
}
