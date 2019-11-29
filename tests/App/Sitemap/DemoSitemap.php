<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Tests\App\Sitemap;

use Core23\SitemapBundle\Definition\SitemapDefinitionInterface;
use Core23\SitemapBundle\Model\Url;
use Core23\SitemapBundle\Sitemap\SitemapServiceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class DemoSitemap implements SitemapServiceInterface
{
    public function configureSettings(OptionsResolver $resolver): void
    {
    }

    public function execute(SitemapDefinitionInterface $sitemap): array
    {
        return [
            new Url('example.com'),
        ];
    }
}
