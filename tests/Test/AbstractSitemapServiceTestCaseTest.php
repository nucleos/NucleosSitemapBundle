<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Tests\Test;

use Core23\SitemapBundle\Definition\SitemapDefinitionInterface;
use Core23\SitemapBundle\Model\Url;
use Core23\SitemapBundle\Sitemap\SitemapServiceInterface;
use Core23\SitemapBundle\Test\AbstractSitemapServiceTestCase as ParentTestCase;
use DateTime;
use PHPUnit\Framework\AssertionFailedError;

class AbstractSitemapServiceTestCaseTest extends ParentTestCase
{
    private $serviceMock;

    protected function setUp(): void
    {
        $this->serviceMock = $this->prophesize(SitemapServiceInterface::class);

        parent::setUp();
    }

    public function testAssertSitemapCount(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('Failed asserting that actual size 2 matches expected size 1.');

        $sitemap = $this->prophesize(SitemapDefinitionInterface::class);

        $this->serviceMock->execute($sitemap)
            ->willReturn([
                new Url('/path/foo', 20, Url::FREQUENCE_DAILY),
                new Url('/path/bar', 20, Url::FREQUENCE_DAILY),
            ])
        ;

        $this->assertSitemap('/path/bar', 20, Url::FREQUENCE_DAILY);

        $this->process($sitemap->reveal());
    }

    public function testAssertUrlNotCalled(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage("The url '/path/foo' was not expected to be called.");

        $sitemap = $this->prophesize(SitemapDefinitionInterface::class);

        $this->serviceMock->execute($sitemap)
            ->willReturn(
                [
                    new Url('/path/foo', 20, Url::FREQUENCE_DAILY),
                ]
            )
        ;

        $this->assertSitemap('/path/bar', 20, Url::FREQUENCE_DAILY);

        $this->process($sitemap->reveal());
    }

    public function testAssertLastmod(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage("The url '/path/foo' was expected with a different lastmod.");

        $sitemap = $this->prophesize(SitemapDefinitionInterface::class);

        $this->serviceMock->execute($sitemap)
            ->willReturn([
                new Url('/path/foo', 20, Url::FREQUENCE_DAILY, new DateTime('2018-10-02')),
            ])
        ;

        $this->assertSitemap('/path/foo', 20, Url::FREQUENCE_DAILY, new DateTime('2018-10-01'));

        $this->process($sitemap->reveal());
    }

    public function testAssertPriority(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage("The url '/path/foo' was expected with 20 priority. 60 given.");

        $sitemap = $this->prophesize(SitemapDefinitionInterface::class);

        $this->serviceMock->execute($sitemap)
            ->willReturn([
                new Url('/path/foo', 60, Url::FREQUENCE_DAILY),
            ])
        ;

        $this->assertSitemap('/path/foo', 20, Url::FREQUENCE_DAILY);

        $this->process($sitemap->reveal());
    }

    public function testAssertChangeFreq(): void
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage("The url '/path/foo' was expected with weekly changefreq. daily given.");

        $sitemap = $this->prophesize(SitemapDefinitionInterface::class);

        $this->serviceMock->execute($sitemap)
            ->willReturn([
                new Url('/path/foo', 20, Url::FREQUENCE_DAILY),
            ])
        ;

        $this->assertSitemap('/path/foo', 20, Url::FREQUENCE_WEEKLY);

        $this->process($sitemap->reveal());
    }

    /**
     * @return SitemapServiceInterface
     */
    protected function createService(): SitemapServiceInterface
    {
        return $this->serviceMock->reveal();
    }
}
