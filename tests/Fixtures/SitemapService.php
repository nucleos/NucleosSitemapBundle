<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Tests\Fixtures;

use Core23\SitemapBundle\Definition\SitemapDefinitionInterface;
use Core23\SitemapBundle\Model\UrlInterface;
use Core23\SitemapBundle\Sitemap\SitemapServiceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class SitemapService implements SitemapServiceInterface
{
    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver->setDefault('custom', 'foo');
    }

    /**
     * @return UrlInterface[]
     */
    public function execute(SitemapDefinitionInterface $sitemap): array
    {
        return [];
    }
}
