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

interface SitemapServiceManagerInterface
{
    /**
     * Return the block service linked to the link.
     *
     * @param SitemapDefinitionInterface $sitemap
     *
     * @return SitemapServiceInterface|null
     */
    public function get(SitemapDefinitionInterface $sitemap): ?SitemapServiceInterface;

    /**
     * @param string $id
     *
     * @return bool
     */
    public function has($id): bool;

    /**
     * @param string $id
     *
     * @throws SitemapNotFoundException
     *
     * @return SitemapServiceInterface
     */
    public function getService(string $id): SitemapServiceInterface;

    /**
     * @return SitemapServiceInterface[]
     */
    public function getServices(): array;

    /**
     * @param SitemapServiceInterface $service
     */
    public function addSitemap(SitemapServiceInterface $service): void;
}
