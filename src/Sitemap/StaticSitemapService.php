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
use Core23\SitemapBundle\Model\Url;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class StaticSitemapService implements SitemapServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'priority'   => null,
                'url'        => null,
                'changefreq' => null,
            ])
            ->setAllowedTypes('priority', ['null', 'int']);
    }

    /**
     * {@inheritdoc}
     */
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
