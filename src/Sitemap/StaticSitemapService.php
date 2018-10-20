<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Sitemap;

use Core23\SitemapBundle\Model\SitemapInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class StaticSitemapService extends AbstractSitemapService
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
    public function execute(SitemapInterface $sitemap): array
    {
        return [
            $this->createEntry($sitemap->getSetting('url'), $sitemap->getSetting('priority'), $sitemap->getSetting('changefreq')),
        ];
    }
}
