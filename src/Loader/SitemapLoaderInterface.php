<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Loader;

use Core23\SitemapBundle\Exception\SitemapNotFoundException;
use Core23\SitemapBundle\Model\SitemapDefinitionInterface;

interface SitemapLoaderInterface
{
    /**
     * @param array $configuration
     *
     * @throws SitemapNotFoundException if no sitemap with that name is found
     *
     * @return SitemapDefinitionInterface
     */
    public function load(array $configuration): SitemapDefinitionInterface;
}
