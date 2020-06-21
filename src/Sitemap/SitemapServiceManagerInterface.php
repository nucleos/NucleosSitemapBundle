<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SitemapBundle\Sitemap;

use Nucleos\SitemapBundle\Definition\SitemapDefinitionInterface;

interface SitemapServiceManagerInterface
{
    /**
     * Return the block service linked to the link.
     */
    public function get(SitemapDefinitionInterface $definition): ?SitemapServiceInterface;

    /**
     * Adds a new sitemap service.
     */
    public function addSitemap(string $id, SitemapServiceInterface $service): void;
}
