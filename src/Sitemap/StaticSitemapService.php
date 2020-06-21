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
use Nucleos\SitemapBundle\Model\Url;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class StaticSitemapService implements SitemapServiceInterface
{
    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'priority'   => null,
                'url'        => null,
                'changefreq' => null,
            ])
            ->setAllowedTypes('priority', ['null', 'int'])
        ;
    }

    public function execute(SitemapDefinitionInterface $sitemap): array
    {
        if (null === $sitemap->getSetting('url')) {
            return [];
        }

        return [
            new Url($sitemap->getSetting('url'), $sitemap->getSetting('priority'), $sitemap->getSetting('changefreq')),
        ];
    }
}
