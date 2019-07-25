<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Definition;

interface DefintionManagerInterface
{
    /**
     * Returns the sitemaps.
     *
     * @return array<string, SitemapDefinitionInterface>
     */
    public function getAll(): array;

    /**
     * Adds a sitemap definition.
     */
    public function addDefinition(string $id, array $configuration = []): self;
}
