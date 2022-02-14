<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SitemapBundle\Definition;

final class DefintionManager implements DefintionManagerInterface
{
    /**
     * Collection of available sitemap definitions.
     *
     * @var SitemapDefinitionInterface[]
     */
    private array $sitemaps = [];

    public function addDefinition(string $id, array $configuration = []): DefintionManagerInterface
    {
        $this->add(new SitemapDefinition($id, $configuration));

        return $this;
    }

    public function getAll(): array
    {
        return $this->sitemaps;
    }

    private function add(SitemapDefinitionInterface $sitemap): DefintionManagerInterface
    {
        $this->sitemaps[] = $sitemap;

        return $this;
    }
}
