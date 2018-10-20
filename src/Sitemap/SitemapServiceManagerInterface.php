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

interface SitemapServiceManagerInterface
{
    /**
     * Return the block service linked to the link.
     *
     * @param SitemapDefinitionInterface $definition
     *
     * @return SitemapServiceInterface|null
     */
    public function get(SitemapDefinitionInterface $definition): ?SitemapServiceInterface;

    /**
     * Adds a new sitemap service.
     *
     * @param string                  $id
     * @param SitemapServiceInterface $service
     */
    public function addSitemap(string $id, SitemapServiceInterface $service): void;
}
