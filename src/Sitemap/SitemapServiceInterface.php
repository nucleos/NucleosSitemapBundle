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
use Nucleos\SitemapBundle\Model\UrlInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface SitemapServiceInterface
{
    public function configureSettings(OptionsResolver $resolver): void;

    /**
     * @return UrlInterface[]
     */
    public function execute(SitemapDefinitionInterface $sitemap): array;
}
