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
use Symfony\Component\OptionsResolver\OptionsResolver;

interface SitemapServiceInterface
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureSettings(OptionsResolver $resolver): void;

    /**
     * @param SitemapDefinitionInterface $sitemap
     *
     * @return array
     */
    public function execute(SitemapDefinitionInterface $sitemap): array;
}
