<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Tests\Sitemap;

use Core23\SitemapBundle\Model\SitemapDefinition;
use Core23\SitemapBundle\Model\Url;
use Core23\SitemapBundle\Sitemap\SitemapServiceInterface;
use Core23\SitemapBundle\Sitemap\StaticSitemapService;
use Core23\SitemapBundle\Test\AbstractSitemapServiceTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class StaticSitemapServiceTest extends AbstractSitemapServiceTestCase
{
    public function testSitemap(): void
    {
        $sitemap = new SitemapDefinition('demo', [
            'priority'   => 20,
            'url'        => '/foo/bar',
            'changefreq' => Url::FREQUENCE_DAILY,
        ]);

        $optionResolver = new OptionsResolver();
        $this->service->configureSettings($optionResolver);

        $sitemap->setSettings($optionResolver->resolve($sitemap->getSettings()));

        $this->assertSitemap('/foo/bar', 20, Url::FREQUENCE_DAILY);

        $this->process($sitemap);
    }

    /**
     * {@inheritdoc}
     */
    protected function createService(): SitemapServiceInterface
    {
        return new StaticSitemapService(
            $this->router
        );
    }
}
