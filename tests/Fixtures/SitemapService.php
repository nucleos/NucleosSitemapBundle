<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\SitemapBundle\Tests\Fixtures;

use Nucleos\SitemapBundle\Definition\SitemapDefinitionInterface;
use Nucleos\SitemapBundle\Model\UrlInterface;
use Nucleos\SitemapBundle\Sitemap\SitemapServiceInterface;
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
