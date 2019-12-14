<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\SitemapBundle\Tests\Generator;

use Core23\SitemapBundle\Definition\DefintionManagerInterface;
use Core23\SitemapBundle\Generator\SitemapGenerator;
use Core23\SitemapBundle\Model\Url;
use Core23\SitemapBundle\Model\UrlInterface;
use Core23\SitemapBundle\Sitemap\SitemapServiceInterface;
use Core23\SitemapBundle\Sitemap\SitemapServiceManagerInterface;
use Core23\SitemapBundle\Tests\Fixtures\InvalidArgumentException;
use Core23\SitemapBundle\Tests\Fixtures\SitemapDefinitionStub;
use DateTime;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\SimpleCache\CacheInterface;
use RuntimeException;

final class SitemapGeneratorTest extends TestCase
{
    private $sitemapServiceManager;

    private $defintionManager;

    public static function setUpBeforeClass(): void
    {
        date_default_timezone_set('UTC');
    }

    protected function setUp(): void
    {
        $this->sitemapServiceManager = $this->prophesize(SitemapServiceManagerInterface::class);
        $this->defintionManager      = $this->prophesize(DefintionManagerInterface::class);
    }

    public function testToXMLWithInvalidDefinition(): void
    {
        $expected = '<?xml version="1.0" encoding="UTF-8"?>';
        $expected .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ';
        $expected .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $expected .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
        $expected .= '</urlset>';

        $definition = new SitemapDefinitionStub('foo');

        $this->sitemapServiceManager->get($definition)
            ->willReturn(null)
        ;

        $this->defintionManager->getAll()
            ->willReturn([
                'dummy' => $definition,
            ])
        ;

        $generator = new SitemapGenerator(
            $this->sitemapServiceManager->reveal(),
            $this->defintionManager->reveal()
        );

        static::assertSame($expected, $generator->toXML());
    }

    public function testToXMLWithNoEntries(): void
    {
        $expected = '<?xml version="1.0" encoding="UTF-8"?>';
        $expected .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ';
        $expected .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $expected .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
        $expected .= '</urlset>';

        $this->defintionManager->getAll()
            ->willReturn([])
        ;

        $generator = new SitemapGenerator(
            $this->sitemapServiceManager->reveal(),
            $this->defintionManager->reveal()
        );

        static::assertSame($expected, $generator->toXML());
    }

    public function testToXML(): void
    {
        $expected = '<?xml version="1.0" encoding="UTF-8"?>';
        $expected .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ';
        $expected .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $expected .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
        $expected .= '<url><loc>http://core23.de</loc><lastmod>2017-12-23T00:00:00+00:00</lastmod><changefreq>daily</changefreq><priority>80</priority></url>';
        $expected .= '</urlset>';

        $definition = new SitemapDefinitionStub('foo');

        $url = $this->prophesize(UrlInterface::class);
        $url->getChangeFreq()
            ->willReturn(Url::FREQUENCE_DAILY)
        ;
        $url->getLastMod()
            ->willReturn(new DateTime('2017-12-23 00:00:00'))
        ;
        $url->getLoc()
            ->willReturn('http://core23.de')
        ;
        $url->getPriority()
            ->willReturn(80)
        ;

        $sitemap = $this->prophesize(SitemapServiceInterface::class);
        $sitemap->execute($definition)
            ->willReturn([
                $url->reveal(),
            ])
        ;

        $this->sitemapServiceManager->get($definition)
            ->willReturn($sitemap)
        ;

        $this->defintionManager->getAll()
            ->willReturn([
                'dummy' => $definition,
            ])
        ;

        $generator = new SitemapGenerator(
            $this->sitemapServiceManager->reveal(),
            $this->defintionManager->reveal()
        );

        static::assertSame($expected, $generator->toXML());
    }

    public function testToXMLWithExistingCache(): void
    {
        $xmlEntry = '<url><loc>http://core23.de</loc><lastmod>2017-12-23T00:00:00+00:00</lastmod><changefreq>daily</changefreq><priority>80</priority></url>';

        $expected = '<?xml version="1.0" encoding="UTF-8"?>';
        $expected .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ';
        $expected .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $expected .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
        $expected .= $xmlEntry;
        $expected .= '</urlset>';

        $definition = new SitemapDefinitionStub('foo');

        $url = $this->prophesize(UrlInterface::class);
        $url->getChangeFreq()
            ->willReturn(Url::FREQUENCE_DAILY)
        ;
        $url->getLastMod()
            ->willReturn(new DateTime('2017-12-23 00:00:00'))
        ;
        $url->getLoc()
            ->willReturn('http://core23.de')
        ;
        $url->getPriority()
            ->willReturn(80)
        ;

        $sitemap = $this->prophesize(SitemapServiceInterface::class);

        $this->sitemapServiceManager->get($definition)
            ->willReturn($sitemap)
        ;

        $this->defintionManager->getAll()
            ->willReturn([
                'dummy' => $definition,
            ])
        ;

        $cache = $this->prophesize(CacheInterface::class);
        $cache->has(Argument::containingString('Sitemap_'))
            ->willReturn(true)
        ;
        $cache->get(Argument::containingString('Sitemap_'))
            ->willReturn($xmlEntry)
        ;

        $generator = new SitemapGenerator(
            $this->sitemapServiceManager->reveal(),
            $this->defintionManager->reveal(),
            $cache->reveal()
        );

        static::assertSame($expected, $generator->toXML());
    }

    public function testToXMLWithExpiredCache(): void
    {
        $xmlEntry = '<url><loc>http://core23.de</loc><lastmod>2017-12-23T00:00:00+00:00</lastmod><changefreq>daily</changefreq><priority>80</priority></url>';

        $expected = '<?xml version="1.0" encoding="UTF-8"?>';
        $expected .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ';
        $expected .= 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $expected .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
        $expected .= $xmlEntry;
        $expected .= '</urlset>';

        $definition = new SitemapDefinitionStub('example');

        $url = $this->prophesize(UrlInterface::class);
        $url->getChangeFreq()
            ->willReturn(Url::FREQUENCE_DAILY)
        ;
        $url->getLastMod()
            ->willReturn(new DateTime('2017-12-23'))
        ;
        $url->getLoc()
            ->willReturn('http://core23.de')
        ;
        $url->getPriority()
            ->willReturn(80)
        ;

        $sitemap = $this->prophesize(SitemapServiceInterface::class);
        $sitemap->execute($definition)
            ->willReturn([
                $url->reveal(),
            ])
        ;

        $this->sitemapServiceManager->get($definition)
            ->willReturn($sitemap)
        ;

        $this->defintionManager->getAll()
            ->willReturn([
                'dummy' => $definition,
            ])
        ;

        $cache = $this->prophesize(CacheInterface::class);
        $cache->has(Argument::containingString('Sitemap_'))
            ->willReturn(false)
        ;
        $cache->set(Argument::containingString('Sitemap_'), $xmlEntry, 42)
            ->shouldBeCalled()
        ;

        $generator = new SitemapGenerator(
            $this->sitemapServiceManager->reveal(),
            $this->defintionManager->reveal(),
            $cache->reveal()
        );

        static::assertSame($expected, $generator->toXML());
    }

    public function testToXMLWithCacheException(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Error accessing cache');

        $definition = new SitemapDefinitionStub('example');

        $this->sitemapServiceManager->get($definition)
            ->willReturn(null)
        ;

        $this->defintionManager->getAll()
            ->willReturn([
                'dummy' => $definition,
            ])
        ;

        $cache = $this->prophesize(CacheInterface::class);
        $cache->has(Argument::containingString('Sitemap_'))
            ->willThrow(InvalidArgumentException::class)
        ;

        $generator = new SitemapGenerator(
            $this->sitemapServiceManager->reveal(),
            $this->defintionManager->reveal(),
            $cache->reveal()
        );

        $generator->toXML();
    }
}
